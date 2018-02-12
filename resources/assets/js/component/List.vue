<template>
  <div >
    <div  v-if="!loading">
    <div class="row restaurant"  v-for="store in stores" :key="store.store_id" >
        <div class=" col-xs-12 col-sm-12 col-md-12">
            <div class="border-box">
                <div class="restaurant-detail">
                      <a v-bind:href="'/'+store.code_store+'/storelisting/index/product'" style="color: black">

                        <div class="col-md-2 col-xs-4 restaurant-detail-left">
                            <div class="restaurant-logo">
                                <img  :src="'/media/storelisting/'+store.img" alt="">
                            </div>
                        </div>
                    </a>
                    <div class="col-md-10 col-xs-8 restaurant-detail-right">
                        <div class="row">
                            <a :href="'/'+store.code_store+'/storelisting/index/product'" style="color: black">

                                <div class="col-md-8 col-xs-12">
                                    <div class="restaurant-name"><h3>{{store.namestore}}</h3></div>
                                    <div class="restaurant-address">{{store.address}}</div>
                                </div>
                            </a>
                            <div class="col-md-4 col-xs-12">
                                <div class="restaurant-item-start">
                                    <div class="count-review" data-store-id=""
                                         style="cursor: pointer">
                                        ()
                                    </div>

                                    <div class="rating-box">
                                                                     </div>

                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="restaurant-info">
                    <div class="col-md-4 col-xs-4">
                        <div class="restaurant-feature-cart-icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        </div>
                        <div class="restaurant-feature-cart-text"><span>{{store.lowest}}</span></div>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <div class="restaurant-feature-cart-icon"><i class="fa fa-bicycle" aria-hidden="true"></i></div>
                        <div class="restaurant-feature-cart-text"><span>{{store.distance}} KM</span></div>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <div class="restaurant-feature-cart-icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                        <div class="restaurant-feature-cart-text"><span>{{store.time_open}}</span></div>

                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    <div v-else style="text-align:center">
        <img src="/media/storelisting/Loading_icon.gif" alt="">
    </div>
  </div>
</template>
<script>
import axios from 'axios';
    export default {
        name:'List',
        data(){
            return {
                stores:[ ],
                loading:true
            }
        },
        created:function(){
            var app = this;
            var url_string = window.location.href
            var url = new URL(url_string);
            var lng = url.searchParams.get("lng");
            var lat = url.searchParams.get("lat");
            var url = window.location.href.split('/')[3];
            var bodyFormData = new FormData();
            bodyFormData.set('lat',lat);
            bodyFormData.set('lng',lng);

            axios({
                method: 'post',
                url: '/'+url+'/storelisting/index/stores',
                data:bodyFormData ,
                config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
            .then((data)=>{
                app.loading = false;
                app.stores = data.data
               
            })
            .catch()
        }
    }
</script>