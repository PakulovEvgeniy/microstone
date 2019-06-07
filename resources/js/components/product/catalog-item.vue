<template>
    <div class="catalog-item" @mouseenter="inItem=true" @mouseleave="inItem=false">
      <div class="n-catalog-product">
        <div class="n-catalog-product__main">
          <div class="n-catalog-product__info">
            <div class="product-info">
              <div class="product-info__image">
                <a>
                  <picture>
                    <img :src="item.image">
                  </picture>
                </a>
                <div class="product-info__code">
                  <span>Код:</span>
                  <span>{{item.sku}}</span>
                </div>
              </div>
              <div class="product-info__title">
                <div class="product-info__title-link">
                  <a class="ui-link">{{item.name}}</a>
                </div>
                <span class="product-info__title-description">
                  {{item.small_desc}}
                </span>
                <div class="product-info__voblers">
                  <div class="w-product-voblers">
                    <div class="vobler">Акция</div>
                  </div>
                </div>
              </div>
              <div class="product-info__social">
                <div class="product-info__stat">
                  <div class="product-info__rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                  </div>
                  <a class="product-info__comments-count ui-link ui-link_blue">
                    <i class="far fa-comment-alt"></i>
                    &nbsp;1
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="n-catalog-product__price">
            <div class="product-price">
              <div class="product-price__current">
                450.50 - 600.00
                <i class="product-price__rub_icon fas fa-ruble-sign"></i>
              </div>
            </div>
          </div>
          <div class="n-catalog-product__buttons">
            <button v-tooltip.top="'Добавить в избранное'" class="button-ui button-ui_white button-ui_icon wishlist-btn">
              <i class="far fa-heart"></i>
            </button>
            <button v-tooltip.top="'Добавить в лист ожидания'" class="button-ui button-ui_white button-ui_icon waitlist-btn">
              <i class="far fa-clock"></i>
            </button>
            <div class="primary-btn">
              <button class="button-ui button-ui_brand" :class="{'button-ui_passive' : !inItem}">Купить</button>
            </div>
          </div>
        </div>
        <div class="n-catalog-product__footer">
          <div class="n-catalog-product__avails">
            <div class="order-avail-wrap">
              <span class="avail-text">
                <span class="available">Доступно:</span>
                <a class="avail-text__link ui-link ui-link_blue ui-link_pseudolink">В наличии</a>
                <a class="avail-text__link ui-link ui-link_blue ui-link_pseudolink">Под заказ</a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
 
    export default {
        data() {
            return {
              inItem: false
            }
        },
        props: [
          'item'
        ]
    }
</script>

<style>
  .product-price__current {
    font-size: 20px;
    font-weight: bold;
  }
  .product-price__current i {
    color: #afafaf;
    font-size: 18px;
  }
  .order-avail-wrap {
    font-size: 13px;
    line-height: 13px;
  }
  .order-avail-wrap .avail-text .available {
    display: inline-block;
    margin-right: 5px;
    line-height: 15px;
    overflow: hidden;
  }
  .order-avail-wrap .avail-text__link {
    display: inline-block;
    line-height: 13px;
    overflow: hidden;
    position: relative;
    text-transform: lowercase;
    margin-right: 5px;
  }
  .avail-text a:last-child {
    margin-right: 0;
  }
  .product-info__rating i {
    color: #feb909;
    font-size: 12px;
  }
</style>