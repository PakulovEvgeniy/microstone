<template>
    <div v-title="calcTitle">
      <bread-crump v-show="getScreenState>1" :links="breadItems"></bread-crump>
      <div class="manuf-page__content">
          <div class="manuf-page__left">
              <div class="left-manuf">
                  <div class="toc-index">
                      <h4>Производители</h4>
                      <div class="toc-index-list">
                        <anchor-router-link v-for="(el, ind) in brandsByLetter" :to="{hash: '#toc-L'+ind}" :key="ind"
                          :scrollOptions="{
                              container: 'body',
                              duration: 700,
                              easing: 'ease',
                              offset: -70
                            }"
                        >
                          {{ind}}
                        </anchor-router-link>
                      </div>
                  </div>
                  <div class="toc-list">
                    <template v-for="(el, ind) in brandsByLetter">
                      <h4 class="toc-letter" :key="ind" :id="'toc-L'+ind">{{ind}}</h4>
                      <router-link v-for="it in el" :key="it.id" :to="'/manufacturer/' + it.chpu">
                        {{it.name}}
                      </router-link>
                    </template>
                  </div>
              </div>
          </div>
          <div class="manuf-page__list">
            <h1>{{calcTitle}}</h1>
            <template v-if="!curManufacture">
              <article class="post-item" v-for="it in brandsPagyPage" :key="it.id">
                <div class="post-item-image">
                  <router-link class="producer-logo" :to="'/manufacturer/'+it.chpu" :title="'Перейти к карточке производителя ' + it.full_name">
                    <img :src="it.image" width="100" height="40" :alt="it.full_name">
                  </router-link>
                </div>
                <div class="post-item-text">
                  <h3 class="post-item-header">
                    <router-link :to="'/manufacturer/'+it.chpu">{{it.full_name}}</router-link>
                  </h3>
                  <p class="post-item-passage">
                    {{it.description}}
                    <router-link class="readmore" :to="'/manufacturer/'+it.chpu">...читать
                      <span class="tri tri-right"></span></router-link>
                  </p>
                </div>
              </article>
              <paginator v-if="itemQty>0" :itemQty="itemQty" :numPage="pageM" @changePage="onChangePage($event)"></paginator>
            </template>
            <template v-else>
              <div class="post-item">
                <div class="brand-img">
                  <router-link class="producer-logo" :to="'/manufacturer/'+curManufacture.chpu" :title="'Перейти к карточке производителя ' + curManufacture.full_name">
                    <img :src="curManufacture.image" width="100" height="40" :alt="curManufacture.full_name">
                   </router-link>
                </div>
                <ul class="manufacturer-info">
                  <li>Полное наименование: <strong>{{curManufacture.full_name}}</strong>
                    <span v-show="curManufacture.name!=curManufacture.full_name"> ({{curManufacture.name}})</span>
                  </li>
                  <li v-if="curManufacture.cite">Web-сайт: <a :href="'http://'+curManufacture.cite" target="_blank" rel="nofollow noopener">{{curManufacture.cite}}</a></li>
                </ul>
                <div v-if="curManufacture.description" class="manufacturer-about">
                  <h2>О компании {{curManufacture.full_name}}</h2>
                  <div v-html="curManufacture.description"></div>
                </div>
              </div>
              <item-tabs :tabs="tabs" :active="activeTab" @changeTab="activeTab=$event">
                <div v-show="activeTab==0">
                  <div class="subpages">
                    <div class="subpages-item" v-for="it in brandsCat" :key="it.idP">
                      <img :src="it.imageP" width="80" height="80" :alt="it.nameP">
                      <h2>{{it.nameP}}</h2>
                      <ul>
                        <li v-for="el in it.child" :key="el.id">
                          <router-link :to="el.chpu">
                            <img :src="el.image" width="35" height="35" :alt="el.name">
                            <span>{{el.name}}</span>
                          </router-link>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </item-tabs>
            </template>
          </div>
      </div>
      
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import AnchorRouterLink from 'vue-anchor-router-link';
    import paginator from '../system/paginator.vue';
    import itemTabs from '../system/item-tabs.vue';
    import Breadcrump from '../system/breadcrump.vue';

    export default {
        data() {
            return {
                title: 'Производители',
                activeTab: 0
            }
        },
        computed: {
          ...mapGetters([
            'brands',
            'getScreenState',
            'pageManuf',
            'curBrand'
          ]),
          tabs() {
            return [
              {
                'id': 'catalog',
                'name': 'Товары производителя'
              },
              {
                'id': 'news',
                'name': 'Новости'
              }
            ]
          },
          brandsByLetter() {
            let res = {}
            this.brands.sort((a, b) => {
              if (a.name<b.name) {
                return -1;
              } else if (a.name>b.name) {
                return 1;
              } else {
                return 0;
              }
            }).forEach((el) => {
              let l = el.name[0].toUpperCase();
              if (!res[l]) {
                res[l] = [];
              }
              res[l].push(el)
            })
            return res;
          },
          brandsPagy() {
            return this.brands.filter((el) => {
              return el.description.length>0
            }).sort((a, b) => {
              return a.kod_sort - b.kod_sort;
            })
          },
          itemQty() {
            return this.brandsPagy.length;
          },
          brandsPagyPage() {
            return this.brandsPagy.slice((this.pageM-1)*18, this.pageM*18);
          },
          pageM() {
            let p = +this.pageManuf || 1;
            if (p>Math.ceil(this.itemQty/18)) {
              p = 1;
            }
            return p;
          },
          curManufacture() {
            if (this.$route.params.id) {
                let it = this.findItem(this.$route.params.id);
                if (it) {
                  return this.curBrand;
                }
            }
          },
          breadItems() {
            let res = [
              {
                id: 0,
                name: 'Главная',
                link: '/'
              },
              {
                id: 1,
                name: 'Производители',
                link: '/manufacturer'
              }
            ];

            if (this.curManufacture) {
              res.push({
                id: 2,
                name: this.curManufacture.full_name,
                link: '/manufacturer/'+this.curManufacture.chpu
              });
            }

            return res;
          },
          calcTitle() {
            return this.curManufacture ? this.curManufacture.full_name : this.title;
          },
          brandsCat() {
            if (!this.curBrand.brandsCat) {
              return;
            }
            let res = [];
            for (let key in this.curBrand.brandsCat) {
              res.push(this.curBrand.brandsCat[key]);
            }
            return res.sort((a, b) => {
              return a.kod_sort - b.kod_sort;
            });
          }
        },
        methods: {
           onChangePage(pg) {
             this.$router.push({
                path: this.$router.currentRoute.path,
                query: {page: pg}
             });
             let scrTop = this.getScreenState == 1 ? 40 : 70;
             window.scrollTo({ top: scrTop, behavior: 'smooth' });
           },
           findItem(id) {
            return this.brands.find((el) => {
               return el.chpu == id; 
            })
           } 
        },
        components: {
          AnchorRouterLink,
          paginator,
          itemTabs,
          'bread-crump': Breadcrump
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        }
    }
</script>

<style>
  .manuf-page__left {
    height: 100%;
    margin-right: 20px;
  }
  .manuf-page__list {
    max-width: 902px;
    width: 100%;
  }
  .manuf-page__content {
      display: flex;
      margin-top: 10px;
  }
  .left-manuf, .post-item {
    position: relative;
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
  }
  .toc-index {
    line-height: 1.5em;
    margin: 0 0 2em;
  }
  .toc-index h4 {
    font-size: 1.3em;
    color: #444;
    text-align: center;
    font-weight: normal;
  }
  .toc-index-list {
    text-align: center;
    line-height: 1.4em;
    margin: 0 0 1.5em;
    padding: 0 20px;
  }
  .toc-index a {
    font: 16px monospace;
    padding: 0 .25em;
    color: #1d71b8;
  }
  .toc-letter {
    margin: 1em 0 .6em;
    text-align: center;
    color: #fff;
    background-color: #77aad4;
    font-weight: normal;
  }
  .toc-list {
    padding: 0 20px;
  }
  .toc-list a {
    display: block;
    color: #1d71b8;
    line-height: 1.2em;
    margin-bottom: .6em;
    font-size: 15px;
  }
  .toc-list a:hover, .toc-index a:hover {
    text-decoration: none;
    color: tomato;
  }
  .manuf-page__list h1 {
    font-size: 28px;
    margin-top: 20px;
    font-weight: normal;
    margin-top: 0;
  }
  .post-item {
    margin-bottom: 10px;
    padding: 20px 20px 5px 20px;
  }
  .post-item-image {
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    height: 50px;
    width: 150px;
    border-top: 2px solid #fff;
    border-bottom: 2px solid #fff;
    font-size: 0;
    border-radius: 8px;
    float: left;
  }
  .post-item-text {
    vertical-align: top;
    margin-left: 170px;
    min-width: 200px;
    position: relative;
    min-height: 50px;
  }
  .post-item-text h3 {
    padding: 0;
    margin-top: 0;
    margin-bottom: 1em;
    font-size: 1.2em;
    font-weight: normal;
  }
  .post-item-text a{
    color: #1d71b8;
  }
  .post-item-text a:hover{
    color: tomato;
    text-decoration: none;
  }
  .post-item-passage {
    margin-bottom: 1em;
    text-align:justify;
    max-height: 4.2em;
    overflow: hidden;
    line-height: 1.4em;
    position: relative;
  }
  .readmore {
    white-space: nowrap;
    text-decoration: none;
    position: absolute;
    right: 0;
    bottom: 0;
    padding-left: 60px;
    background: linear-gradient(90deg,transparent 0,#fff 40px,#fff);
  }
  .tri {
    line-height: 0;
    display: inline-block;
    width: 0;
    height: 0;
    vertical-align: middle;
    border: .35em solid transparent;
  }
  .tri.tri-right {
    margin-left: .5em;
    margin-top: -.15em;
    border-left-color: tomato;
  }
  .brand-img {
    padding: 5px;
    float: right;
  }
  .manufacturer-info {
    margin: 0 0 1em 15px;
    padding: 0;
    list-style-type: square;
    min-height: 45px;
  }
  .manufacturer-info a {
    color: #1d71b8;
    cursor: pointer;
  }
  .manufacturer-info a:hover {
    color: tomato;
    text-decoration: none;
  }
  .manufacturer-about h2{
    color: #333;
    font-weight: normal;
    margin-top: 2em;
  }
  .manufacturer-about p {
    text-align: justify;
  }
  .subpages {
    padding: 30px 20px;
  }
  .subpages h2 {
    margin-top: 0;
    margin-bottom: 0;
    font-size: 1.4em;
    line-height: 80px;
    font-weight: normal;
    color: #1d71b8;
  }
  .subpages-item {
    position: relative;
    margin-bottom: 20px;
  }
  .subpages-item:last-child {
    margin-bottom: 0;
  }
  .subpages-item::after {
    display: table;
    content: " ";
    clear: both;
  }
  .subpages img {
    display: block;
    float: right;
  }
  .subpages li img {
    display: inline-block;
    float: none;
    vertical-align: top; 
  }
  .subpages li span {
    margin-left: 10px;
    display: inline-block;
    line-height: 35px;
    vertical-align: top;
    color: #1d71b8; 
  }
  .subpages li a:hover {
    text-decoration: none;
  }
  .subpages li a:hover span{
    color: tomato;
  }
  @media (min-width: 1200px) {
    .manuf-page__left {
      width: 278px;
      margin-right: 20px;
    }
    .manuf-page__list {
        width: calc(100% - 278px - 20px);
    }
  }
</style>