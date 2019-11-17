<template>
    <div class="account-wishlist">
      <div class="account-wishlist__info">
          <div class="main-block">
            <div class="name">Список товаров {{curDate}}</div>
            <div class="products-count">{{qtyWishlistText}}</div>
          </div>
      </div>
      <div class="account-wishlist__body">
        <div v-if="countWishlist==0">
          <div class="add-product-to-wish">
            <div class="b-icon">
              <i class="fa fa-heart-o"></i>
            </div>
            <div class="b-text">
              <h4>Добавьте товар в избранное</h4>
              <p>В этом списке будут храниться товары, которые Вас заинтересовали.<br>
                Можете добавить сюда недавно просмотренные товары.
              </p><br>
              <p>
                <b>Внимание!</b><br>
                Чтобы сохранить список и иметь доступ к нему с различных устройств,<br>
                необходимо
                <router-link to="/login">авторизоваться</router-link> или 
                <router-link to="/register">зарегистрироваться</router-link>
              </p><br>
              <p>Кроме того, зарегистрированные пользователи,<br>получают возможность загружать <b>произвольные спецификации</b><br>
                и осуществлять поиск оптимальных предложений.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
            }
        }, 
        props: [
        ],
        computed: {
          ...mapGetters([
           'auth',
           'countWishlist',
           'wishlist'
          ]),
          curDate() {
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth() + 1; //January is 0!
            let yyyy = today.getFullYear();
            if (dd < 10) {
              dd = '0' + dd;
            } 
            if (mm < 10) {
              mm = '0' + mm;
            } 
            return dd + '.' + mm + '.' + yyyy;
          },
          qtyWishlistText() {
            if (!this.countWishlist) {
              return 'Нет товаров';
            }
            return this.countWishlist + ' товаров';
          }
        }
    }
</script>

<style lang="less">
  .account-wishlist {
    &__info {
      border: 1px solid #ddd;
      padding: 20px;
      .main-block {
        position: relative;
        .name {
          font-size: 18px;
          margin-bottom: 5px;
          word-wrap: break-word;
        }
        .products-count {
          color: gray;
          font-size: 13px;
        }
      }
    }
    &__body {
      border: 1px solid #ddd;
      border-top: none;
      .add-product-to-wish {
        padding: 30px;
        .b-icon {
          color: #d8d8d8;
          display: inline-block;
          font-size: 60px;
          margin-right: 20px;
          vertical-align: top;
        }
        .b-text {
          color: gray;
          display: inline-block;
          h4 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 20px;
          }
          p {
            margin: 0;
          }
          a {
            color: #0094d9;
            text-decoration: none;
            &:hover {
              color: #00608d;
              text-decoration: underline;
            }
          }
        }
      }
    }
  }

  @media (min-width: 992px) {
    .account-wishlist {
      &__info {
        border-top-right-radius: 8px;
        border-top-left-radius: 8px;
      }
      &__body {
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
      }
    }
  }
</style>