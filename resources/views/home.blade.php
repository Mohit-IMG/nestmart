@extends('layouts/app')

@section('title',__(' Home'))

@section('content')



<main class="main"> 

         <section class="home-slider position-relative mb-30">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
               <ol class="carousel-indicators">
                  @foreach($sliderResult as $key => $slider)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
                  @endforeach
               </ol>
               <div class="carousel-inner">
                  @foreach($sliderResult as $key => $slider)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                           <img class="d-block w-100" src="{{ asset($slider['image']) }}" alt="Slide {{ $key }}" style="width: 800px; height: 400px;">
                        </div>
                  @endforeach
               </div>
               <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only"></span>
               </a>
               <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only"></span>
               </a>
            </div>
         </section>
         <!--End hero slider-->
         <section class="popular-categories section-padding">
            <div class="container wow animate__animated animate__fadeIn">
               <div class="section-title">
                  <div class="title">
                     <h3>Featured Categories</h3>
                  </div>
                  <div class="slider-arrow slider-arrow-2 flex-right carausel-10-columns-arrow" id="carausel-10-columns-arrows"></div>
               </div>
               <div class="carausel-10-columns-cover position-relative">
                  <div class="carausel-10-columns" id="carausel-10-columns">
                     @foreach($categoryResult as $category)
                     <div class="card-2 bg-10 wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                        <figure class="img-hover-scale overflow-hidden">
                           <a href="{{ url('category/'.$category['slug']) }}"><img src="{{asset('uploads/category/'.$category['image'])}}" alt="" style="height: 80px;width: 80px;" /></a>
                        </figure>
                        <h6><a href="{{ url('category/'.$category['slug']) }}">{{$category['name']}}</a></h6>
                        <span>{{\App\Helpers\commonHelper::getTotalProductByCategory($category['id'])}} items</span>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </section>
         <!--End category slider-->

         <section class="product-tabs section-padding position-relative">
            <div class="container">
               <div class="section-title style-2 wow animate__animated animate__fadeIn">
                  <h3> Top Selling Product </h3>
               </div>
               <!--End nav-tabs-->
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                     <div class="row product-grid-4">
                        @foreach($productResult as $product)
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="{{ url('product_detail/'.$product['slug']) }}">
                                    <img class="default-img" src="{{$product['first_image']}}" alt="" style="height: 228px;" />
                                    <img class="hover-img" src="{{$product['second_image']}}" alt="" style="height: 228px;" />
                                    </a>
                                 </div>
                              </div>
                              <div class="product-content-wrap" style="height:180px;">
                                 <h2><a href="{{ url('product_detail/'.$product['slug']) }}">{{$product['name']}}</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <!-- <input type = 'hidden' value = "{{$product['varientProductId']}}" id ="product_id" name="product_id" > 
                                 <input type="hidden" name="product_qty" id="product_qty"  class="qty-val qty" value="1"> -->
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>₹{{$product['offer_price']}}</span>
                                       <span class="old-price">₹{{$product['sale_price']}}</span>
                                    </div>
                                    <div class="add-cart product">
                                       <input type="hidden" class="product-id" value="{{$product['varientProductId']}}">
                                       <input type="hidden" class="product-qty" value="1">
                                       <button type="submit" class="button button-add-to-cart" id="addtocart"><i class="fi-rs-shopping-cart"></i>Add</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endforeach
                     </div>
                  </div>
               </div>
               <!--End tab-content-->
            </div>
         </section>
         <!--Products Tabs-->
         <section class="section-padding pb-5">
            <div class="container">
               <div class="section-title wow animate__animated animate__fadeIn">
                  <h3 class=""> Featured Products </h3>
               </div>
               <div class="row">
                  <div class="col-lg-3 d-none d-lg-flex wow animate__animated animate__fadeIn">
                     <div class="banner-img style-2">
                        <div class="banner-text">
                           <h2 class="mb-100">Bring nature into your home</h2>
                           <a href="shop-grid-right.html" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-9 col-md-12 wow animate__animated animate__fadeIn" data-wow-delay=".4s">
                     <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                           <div class="carausel-4-columns-cover arrow-center position-relative">
                              <div class="slider-arrow slider-arrow-2 carausel-4-columns-arrow" id="carausel-4-columns-arrows"></div>
                              <div class="carausel-4-columns carausel-arrow-center" id="carausel-4-columns">
                              @foreach($productResult as $product)
                                 <div class="product-cart-wrap">
                                    <div class="product-img-action-wrap">
                                       <div class="product-img product-img-zoom">
                                          <a href="{{ url('product_detail/'.$product['slug']) }}">
                                             <img class="default-img" src="{{$product['first_image']}}" alt="" style="height: 228px;" />
                                             <img class="hover-img" src="{{$product['second_image']}}" alt="" style="height: 228px;" />
                                          </a>
                                       </div>
                                       <div class="product-action-1 w-auto">
                                          <a aria-label="Quick view" class="action-btn small hover-up" data-bs-toggle="modal" data-bs-target="#quickViewModal" data-product='{{ json_encode($product) }}'> <i class="fi-rs-eye"></i></a>
                                          <a aria-label="Add To Wishlist" class="action-btn small hover-up" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                       </div>
                                       <div class="product-badges product-badges-position product-badges-mrg">
                                          <span class="hot">Save {{round($product['discount_amount'])}}%</span>
                                       </div>
                                    </div>
                                    <div class="product-content-wrap" style="height:175px;">
                                       {{-- <h2 class="my-3"><a href="shop-product-right.html" >{{$product['name']}}</a></h2> --}}
                                       <h2 class="my-3">
                                          <a href="shop-product-right.html" style="
                                              display: -webkit-box;
                                              -webkit-box-orient: vertical;
                                              overflow: hidden;
                                              -webkit-line-clamp: 1;
                                              text-overflow: ellipsis;
                                          ">
                                              {{$product['name']}}
                                          </a>
                                      </h2>
                                      
                                       <div class="product-rate d-inline-block my-1">
                                          <div class="product-rating" style="width: 80%"></div>
                                       </div>
                                       <div class="product-price mt-10 my-1">
                                          <span>₹{{$product['offer_price']}}</span>
                                          <span class="old-price">₹{{$product['sale_price']}}</span>
                                       </div>
                                       <div class="add-cart product my-1">
                                          <input type="hidden" class="product-id" value="{{$product['varientProductId']}}">
                                          <input type="hidden" class="product-qty" value="1">
                                          <button type="submit" class="button button-add-to-cart" id="addtocart"><i class="fi-rs-shopping-cart"></i>Add to cart</button>
                                       </div>
                                    </div>
                                 </div>
                                    <!--End product Wrap-->
               
                              @endforeach
                              </div>
                           </div>
                        </div>
                        <!--End tab-pane-->
                     </div>
                     <!--End tab-content-->
                  </div>
                  <!--End Col-lg-9-->
               </div>
            </div>
         </section>
         <!--End Best Sales-->
         <!-- TV Category -->
         <section class="product-tabs section-padding position-relative">
            <div class="container">
               <div class="section-title style-2 wow animate__animated animate__fadeIn">
                  <h3>TV Category </h3>
               </div>
               <!--End nav-tabs-->
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                     <div class="row product-grid-4">
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-1-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="hot">Hot</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Snack</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Seeds of Change Organic Quinoa, Brown, </a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$28.85</span>
                                       <span class="old-price">$32.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".2s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-2-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-2-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="sale">Sale</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Hodo Foods</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">All Natural Italian-Style Chicken Meatballs</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 80%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (3.5)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">Stouffer</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$52.85</span>
                                       <span class="old-price">$55.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".3s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-3-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-3-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="new">New</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Snack</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Angie’s Boomchickapop Sweet & Salty Kettle Corn</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 85%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">StarKist</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$48.85</span>
                                       <span class="old-price">$52.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".4s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-4-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-4-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Vegetables</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Foster Farms Takeout Crispy Classic Buffalo </a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$17.85</span>
                                       <span class="old-price">$19.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".5s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-5-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-5-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="best">-14%</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Pet Foods</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Blue Diamond Almonds Lightly Salted Vegetables</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$23.85</span>
                                       <span class="old-price">$25.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                     </div>
                     <!--End product-grid-4-->
                  </div>
               </div>
               <!--End tab-content-->
            </div>
         </section>
         <!--End TV Category -->
         <!-- Tshirt Category -->
         <section class="product-tabs section-padding position-relative">
            <div class="container">
               <div class="section-title style-2 wow animate__animated animate__fadeIn">
                  <h3>Tshirt Category </h3>
               </div>
               <!--End nav-tabs-->
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                     <div class="row product-grid-4">
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-1-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="hot">Hot</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Snack</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Seeds of Change Organic Quinoa, Brown, </a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$28.85</span>
                                       <span class="old-price">$32.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".2s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-2-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-2-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="sale">Sale</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Hodo Foods</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">All Natural Italian-Style Chicken Meatballs</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 80%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (3.5)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">Stouffer</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$52.85</span>
                                       <span class="old-price">$55.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".3s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-3-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-3-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="new">New</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Snack</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Angie’s Boomchickapop Sweet & Salty Kettle Corn</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 85%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">StarKist</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$48.85</span>
                                       <span class="old-price">$52.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".4s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-4-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-4-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Vegetables</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Foster Farms Takeout Crispy Classic Buffalo </a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$17.85</span>
                                       <span class="old-price">$19.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".5s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-5-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-5-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="best">-14%</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Pet Foods</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Blue Diamond Almonds Lightly Salted Vegetables</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$23.85</span>
                                       <span class="old-price">$25.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                     </div>
                     <!--End product-grid-4-->
                  </div>
               </div>
               <!--End tab-content-->
            </div>
         </section>
         <!--End Tshirt Category -->
         <!-- Computer Category -->
         <section class="product-tabs section-padding position-relative">
            <div class="container">
               <div class="section-title style-2 wow animate__animated animate__fadeIn">
                  <h3>Computer Category </h3>
               </div>
               <!--End nav-tabs-->
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                     <div class="row product-grid-4">
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-1-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="hot">Hot</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Snack</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Seeds of Change Organic Quinoa, Brown,   </a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$28.85</span>
                                       <span class="old-price">$32.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".2s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-2-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-2-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="sale">Sale</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Hodo Foods</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">All Natural Italian-Style Chicken Meatballs</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 80%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (3.5)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">Stouffer</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$52.85</span>
                                       <span class="old-price">$55.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".3s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-3-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-3-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="new">New</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Snack</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Angie’s Boomchickapop Sweet & Salty Kettle Corn</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 85%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">StarKist</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$48.85</span>
                                       <span class="old-price">$52.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".4s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-4-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-4-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Vegetables</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Foster Farms Takeout Crispy Classic Buffalo </a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$17.85</span>
                                       <span class="old-price">$19.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                           <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".5s">
                              <div class="product-img-action-wrap">
                                 <div class="product-img product-img-zoom">
                                    <a href="shop-product-right.html">
                                    <img class="default-img" src="assets/imgs/shop/product-5-1.jpg" alt="" />
                                    <img class="hover-img" src="assets/imgs/shop/product-5-2.jpg" alt="" />
                                    </a>
                                 </div>
                                 <div class="product-action-1">
                                    <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                    <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                    <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                 </div>
                                 <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="best">-14%</span>
                                 </div>
                              </div>
                              <div class="product-content-wrap">
                                 <div class="product-category">
                                    <a href="shop-grid-right.html">Pet Foods</a>
                                 </div>
                                 <h2><a href="shop-product-right.html">Blue Diamond Almonds Lightly Salted Vegetables</a></h2>
                                 <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                       <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                 </div>
                                 <div>
                                    <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                 </div>
                                 <div class="product-card-bottom">
                                    <div class="product-price">
                                       <span>$23.85</span>
                                       <span class="old-price">$25.8</span>
                                    </div>
                                    <div class="add-cart">
                                       <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--end product card-->
                     </div>
                     <!--End product-grid-4-->
                  </div>
               </div>
               <!--End tab-content-->
            </div>
         </section>
         <!--End Computer Category -->
         <section class="section-padding mb-30">
            <div class="container">
               <div class="row">
                  <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 wow animate__animated animate__fadeInUp" data-wow-delay="0">
                     <h4 class="section-title style-1 mb-30 animated animated"> Hot Deals </h4>
                     <div class="product-list-small animated animated">
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-1.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Nestle Original Coffee-Mate Coffee Creamer</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-2.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Nestle Original Coffee-Mate Coffee Creamer</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-3.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Nestle Original Coffee-Mate Coffee Creamer</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6 mb-md-0 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                     <h4 class="section-title style-1 mb-30 animated animated">  Special Offer </h4>
                     <div class="product-list-small animated animated">
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-4.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Organic Cage-Free Grade A Large Brown Eggs</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-5.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Seeds of Change Organic Quinoa, Brown, & Red Rice</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-6.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Naturally Flavored Cinnamon Vanilla Light Roast Coffee</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-lg-block wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                     <h4 class="section-title style-1 mb-30 animated animated">Recently added</h4>
                     <div class="product-list-small animated animated">
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-7.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Pepperidge Farm Farmhouse Hearty White Bread</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-8.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Organic Frozen Triple Berry Blend</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-9.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Oroweat Country Buttermilk Bread</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-xl-block wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                     <h4 class="section-title style-1 mb-30 animated animated"> Special Deals </h4>
                     <div class="product-list-small animated animated">
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-10.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Foster Farms Takeout Crispy Classic Buffalo Wings</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-11.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">Angie’s Boomchickapop Sweet & Salty Kettle Corn</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                        <article class="row align-items-center hover-up">
                           <figure class="col-md-4 mb-0">
                              <a href="shop-product-right.html"><img src="assets/imgs/shop/thumbnail-12.jpg" alt="" /></a>
                           </figure>
                           <div class="col-md-8 mb-0">
                              <h6>
                                 <a href="shop-product-right.html">All Natural Italian-Style Chicken Meatballs</a>
                              </h6>
                              <div class="product-rate-cover">
                                 <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                 </div>
                                 <span class="font-small ml-5 text-muted"> (4.0)</span>
                              </div>
                              <div class="product-price">
                                 <span>$32.85</span>
                                 <span class="old-price">$33.8</span>
                              </div>
                           </div>
                        </article>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!--End 4 columns-->
         <!--Vendor List -->
         <div class="container">
            <div class="section-title wow animate__animated animate__fadeIn" data-wow-delay="0">
               <h3 class="">All Our Vendor List </h3>
               <a class="show-all" href="shop-grid-right.html">
               All Vendors
               <i class="fi-rs-angle-right"></i>
               </a>
            </div>
            <div class="row vendor-grid">
               <div class="col-lg-3 col-md-6 col-12 col-sm-6 justify-content-center">
                  <div class="vendor-wrap mb-40">
                     <div class="vendor-img-action-wrap">
                        <div class="vendor-img">
                           <a href="vendor-details-1.html">
                           <img class="default-img" src="assets/imgs/vendor/vendor-1.png" alt="" />
                           </a>
                        </div>
                        <div class="product-badges product-badges-position product-badges-mrg">
                           <span class="hot">Mall</span>
                        </div>
                     </div>
                     <div class="vendor-content-wrap">
                        <div class="d-flex justify-content-between align-items-end mb-30">
                           <div>
                              <div class="product-category">
                                 <span class="text-muted">Since 2012</span>
                              </div>
                              <h4 class="mb-5"><a href="vendor-details-1.html">Nature Food</a></h4>
                              <div class="product-rate-cover">
                                 <span class="font-small total-product">380 products</span>
                              </div>
                           </div>
                        </div>
                        <div class="vendor-info mb-30">
                           <ul class="contact-infor text-muted">
                              <li><img src="assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Call Us:</strong><span>(+91) - 540-025-124553</span></li>
                           </ul>
                        </div>
                        <a href="vendor-details-1.html" class="btn btn-xs">Visit Store <i class="fi-rs-arrow-small-right"></i></a>
                     </div>
                  </div>
               </div>
               <!--end vendor card-->
               <div class="col-lg-3 col-md-6 col-12 col-sm-6 justify-content-center">
                  <div class="vendor-wrap mb-40">
                     <div class="vendor-img-action-wrap">
                        <div class="vendor-img">
                           <a href="vendor-details-1.html">
                           <img class="default-img" src="assets/imgs/vendor/vendor-2.png" alt="" />
                           </a>
                        </div>
                        <div class="product-badges product-badges-position product-badges-mrg">
                           <span class="hot">Mall</span>
                        </div>
                     </div>
                     <div class="vendor-content-wrap">
                        <div class="d-flex justify-content-between align-items-end mb-30">
                           <div>
                              <div class="product-category">
                                 <span class="text-muted">Since 2012</span>
                              </div>
                              <h4 class="mb-5"><a href="vendor-details-1.html">Nature Food</a></h4>
                              <div class="product-rate-cover">
                                 <span class="font-small total-product">380 products</span>
                              </div>
                           </div>
                        </div>
                        <div class="vendor-info mb-30">
                           <ul class="contact-infor text-muted">
                              <li><img src="assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Call Us:</strong><span>(+91) - 540-025-124553</span></li>
                           </ul>
                        </div>
                        <a href="vendor-details-1.html" class="btn btn-xs">Visit Store <i class="fi-rs-arrow-small-right"></i></a>
                     </div>
                  </div>
               </div>
               <!--end vendor card-->
               <div class="col-lg-3 col-md-6 col-12 col-sm-6 justify-content-center">
                  <div class="vendor-wrap mb-40">
                     <div class="vendor-img-action-wrap">
                        <div class="vendor-img">
                           <a href="vendor-details-1.html">
                           <img class="default-img" src="assets/imgs/vendor/vendor-3.png" alt="" />
                           </a>
                        </div>
                        <div class="product-badges product-badges-position product-badges-mrg">
                           <span class="hot">Mall</span>
                        </div>
                     </div>
                     <div class="vendor-content-wrap">
                        <div class="d-flex justify-content-between align-items-end mb-30">
                           <div>
                              <div class="product-category">
                                 <span class="text-muted">Since 2012</span>
                              </div>
                              <h4 class="mb-5"><a href="vendor-details-1.html">Nature Food</a></h4>
                              <div class="product-rate-cover">
                                 <span class="font-small total-product">380 products</span>
                              </div>
                           </div>
                        </div>
                        <div class="vendor-info mb-30">
                           <ul class="contact-infor text-muted">
                              <li><img src="assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Call Us:</strong><span>(+91) - 540-025-124553</span></li>
                           </ul>
                        </div>
                        <a href="vendor-details-1.html" class="btn btn-xs">Visit Store <i class="fi-rs-arrow-small-right"></i></a>
                     </div>
                  </div>
               </div>
               <!--end vendor card-->
               <div class="col-lg-3 col-md-6 col-12 col-sm-6 justify-content-center">
                  <div class="vendor-wrap mb-40">
                     <div class="vendor-img-action-wrap">
                        <div class="vendor-img">
                           <a href="vendor-details-1.html">
                           <img class="default-img" src="assets/imgs/vendor/vendor-4.png" alt="" />
                           </a>
                        </div>
                        <div class="product-badges product-badges-position product-badges-mrg">
                           <span class="hot">Mall</span>
                        </div>
                     </div>
                     <div class="vendor-content-wrap">
                        <div class="d-flex justify-content-between align-items-end mb-30">
                           <div>
                              <div class="product-category">
                                 <span class="text-muted">Since 2012</span>
                              </div>
                              <h4 class="mb-5"><a href="vendor-details-1.html">Nature Food</a></h4>
                              <div class="product-rate-cover">
                                 <span class="font-small total-product">380 products</span>
                              </div>
                           </div>
                        </div>
                        <div class="vendor-info mb-30">
                           <ul class="contact-infor text-muted">
                              <li><img src="assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Call Us:</strong><span>(+91) - 540-025-124553</span></li>
                           </ul>
                        </div>
                        <a href="vendor-details-1.html" class="btn btn-xs">Visit Store <i class="fi-rs-arrow-small-right"></i></a>
                     </div>
                  </div>
               </div>
               <!--end vendor card-->
            </div>
         </div>
         <!--End Vendor List -->
   </main>


@endsection 

