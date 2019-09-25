<template>
    <div class="not_found" v-title="title">
      <h1>Документ не найден</h1>
      <card>
        <div>Поискать на сайте:</div>
        <main-seach-form :curValue="pathM"></main-seach-form>
      </card>
      <card className="not_found__card">
          <ul class="not_found__list">
              <li class="not_found__list_item"><router-link to="/"><i class="fa fa-home icon"></i><span>Главная страница</span><i class="fa fa-chevron-right"></i></router-link></li>
              <li class="not_found__list_item order"><router-link to="/"><i class="fa fa-book icon"></i><span>Инструкция по работе с сайтом</span><i class="fa fa-chevron-right"></i></router-link></li>
              <li class="not_found__list_item"><router-link to="/category"><i class="fa fa-shopping-bag icon"></i><span>Каталог товаров</span><i class="fa fa-chevron-right"></i></router-link></li>
              <li class="not_found__list_item order"><router-link to="/"><i class="fa fa-shopping-cart icon"></i><span>Порядок осуществления заказа</span><i class="fa fa-chevron-right"></i></router-link></li>
              <li class="not_found__list_item"><router-link to="/manufacturer"><i class="fa fa-industry icon"></i><span>Производители</span><i class="fa fa-chevron-right"></i></router-link></li>
              <li class="not_found__list_item order"><router-link to="/"><i class="fa fa-question icon"></i><span>Часто задаваемые вопросы</span><i class="fa fa-chevron-right"></i></router-link></li>
          </ul>
      </card>
      <div class="not_found__button">
          <div class="primary-btn">
              <button @click="clickReturn" class="button-ui button-ui_brand">Вернуться</button>
          </div>
          <div class="primary-btn">
              <router-link to="/" class="button-ui button-ui_brand">На главную</router-link>
          </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import mainSeachForm from '../forms/main-seach-form.vue';
    import card from '../system/card.vue';
    export default {
        data() {
            return {
                title: 'Интернет-магазин "Микростоун"'
            }
        },
        computed: {
          ...mapGetters([
            'getScreenState'
          ]),
          pathM() {
            let pm = this.$route.path.split('/');
            return pm[pm.length-1];
          }
        },
        methods: {
           clickReturn() {
            this.$router.go(-1);
           } 
        },
        components: {
            mainSeachForm,
            card
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', false);
          })
        }
    }
</script>

<style lang="less">
    .not_found {
        h1 {
          font-size: 28px;
          font-weight: normal;
          margin-top: 0;  
        }
        .main-search-form {
          padding-right: 0;
          padding-left: 0;
          &-container, &-input {
            background: #eaeaea;  
          }  
        }
        &__list {
            display: flex;
            flex-wrap: wrap;
            list-style: square;
            padding: 5px 20px 10px 20px;
            &_item {
                width: 50%;
                a {
                    padding-bottom: 5px;
                    color: #1d71b8;
                    &:hover {
                        text-decoration: none;
                        color: tomato;
                    }
                    i {
                      display: none;
                    }
                }
            }
        }
        &__button {
            display: flex;
            flex-wrap: nowrap;
            .primary-btn {
                text-align: center;
                line-height: 16px;
                vertical-align: middle;
                margin-top: 15px;
                margin-right: 20px;
                button {
                    padding: 0 20px;
                }
                a {
                    padding: 0 20px;
                    line-height: 40px;
                    &:hover {
                        text-decoration: none;
                        color: #fff;
                    }
                }
            }
        }
    }

    @media (max-width: 991px) {
      .not_found {
        &__list {
          list-style: none;
          padding: 0;
          border-top: 1px solid #ddd;
          &_item {
            width: 100%;
            padding: 0 36px;
            height: 60px;
            border-bottom: 1px solid #ddd;
            &.order {
              order: 1;
            }
            a {
              display: flex;
              align-items: center;
              height: 100%;
              padding-bottom: 0;
              color: #333;
              &:hover {
                color: #333;
              }
              span {
                flex-grow: 1;
                padding-left: 10px;
              }
              i {
                display: block !important;
                color: #ccc;
              }
              i.icon {
                color: #7e7e7e;
                font-size: 20px;
                width: 25px;
              }
            }
            &:hover {
              background: #f6f6f6;
            }
          }
        }
        &__button {
          .primary-btn {
            a {
              line-height: 48px;
            }
          }
        }
        &__card.card_info {
          border: none;
          box-shadow: none;
          border-radius: 0;
          padding: 0;
        }
      } 
    }
</style>