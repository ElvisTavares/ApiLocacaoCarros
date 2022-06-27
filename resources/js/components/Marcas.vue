<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

          <!--card de busca-->
          <card-component titulo="Busca de marcas">
              <template v-slot:conteudo>
                <div class="form-row">
                    <div class="col mb-3">
                    
                      <input-container-component 
                        titulo="ID" 
                        id="inputId"
                        id-help="idHelp"
                        texto-ajuda="Opcional. Informe o ID do registro"
                      >
                      <input type="email" class="form-control" id="inputId" aria-describedby="idHelp">
                      </input-container-component>

                    </div>
                    <div class="col mb-3">
                          <input-container-component 
                        titulo="Nome" 
                        id="inputNome"
                        id-help="nomeHelp"
                        texto-ajuda="Opcional. Informe o nome do registro"
                      >
                      <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp">
                      </input-container-component>
                    </div>
                </div>
              </template>

              <template v-slot:rodape>
                   <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
              </template>
          </card-component>
            <!--fim card de busca-->

             <!--card de listagem-->
            <card-component titulo="Relação de marcas">
                <template v-slot:conteudo>
                     <table-component></table-component>
                </template>

                  <template v-slot:rodape>
                      <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                </template>
             </card-component>
            <!--fim card de listagem-->

        <modal-component id="modalMarca" titulo="Adicionar Marca">
          <template v-slot:conteudo>
            <div class="form-group">
              <input-container-component 
                          titulo="Nome da marca" 
                          id="novoNome"
                          id-help="novoNomeHelp"
                          texto-ajuda="Informe o nome do registro"
                        >
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" v-model="nomeMarca">
                       
              </input-container-component>
            </div>

            <div class="form-group">
              <input-container-component 
                          titulo="Imagem" 
                          id="novoImagem"
                          id-help="novoImagemHelp"
                          texto-ajuda="Selecione uma imagem no formato PNG"
                        >
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" @change="carregarImagem($event)">
                        
              </input-container-component>
            </div>

        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
        </modal-component>

        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios';

    export default{
      computed: {
        token(){
          let token = document.cookie.split(';').find(indice => {
            return indice.includes('token=')
          })
          
          token = token.split('=')[1]
          token = 'Bearer ' + token
         
         return token
        }
      },
      data() {
        return {
          urlBase: 'http://localhost:8000/api/v1/marca',
          nomeMarca: '',
          arquivoImagem: []
        }
      },
      methods: {
        carregarImagem(e){
          this.arquivoImagem = e.target.files
        },
        salvar(){
          console.log(this.nomeMarca, this.arquivoImagem)

          let formData = new FormData();
          formData.append('nome', this.nomeMarca)
          formData.append('imagem', this.arquivoImagem[0])

          let config = {
            headers: {
              'Content-Type': 'multipart/form-data',
              'Accept': 'application/json',
              'Authorization': this.token
            }
          }

          axios.post(this.urlBase, formData, config)
              .then(response => {
                console.log(response)
              })
              .catch(errors => {
                console.log(errors)
              })
        }
      }

    }
</script>