@extends('layouts/app')

@section('title',__(' Home'))

@section('content')

<main class="main pages">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Contact
            </div>
        </div>
    </div>
    <div class="page-content pt-50">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 col-lg-12 m-auto">
                    <section class="mb-50">
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="contact-from-area padding-20-row-col">
                                    <h5 class="text-brand mb-10">Contact form</h5>
                                    <h2 class="mb-10">Drop Us a Line</h2>
                                    <p class="text-muted mb-30 font-sm">Your email address will not be published. Required fields are marked *</p>
                                    <form class="contact-form-style mt-30" id="contactForm" action="{{ route('contact') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="input-style mb-20">
                                                    <input name="name" placeholder="Name" type="text" required/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="input-style mb-20">
                                                    <input name="email" placeholder="Your Email" type="email" required/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="input-style mb-20">
                                                    <input name="mobile" placeholder="Your Phone" type="tel" onkeypress="return /[0-9 ]/i.test(event.key)" maxlength="10" required/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="input-style mb-20">
                                                    <input name="subject" placeholder="Subject" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="textarea-style mb-30">
                                                    <textarea name="message" placeholder="Message" required></textarea>
                                                </div>
                                                <button class="submit submit-auto-width" type="submit">Send message</button>
                                            </div>
                                        </div>
                                    </form>
                                    <p class="form-messege"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 pl-50 d-lg-block d-none">
                                <img class="border-radius-15 mt-50" src="assets/imgs/page/contact-2.png" alt="" />
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

<script>

    $(document).ready(function(){
        $('#contactForm').on('submit', function(event){
            event.preventDefault();

            var formAction = $(this).attr('action');

            $.ajax({
                url: formAction,
                data: new FormData(this),
                async: false,
                dataType: 'json',
                type: 'post',
                success: function(response) {

                    if (response.error == false) {
                        toastr.success(response.message);
                        $('#contactForm')[0].reset();
                    
                    } else {
                        
                        toastr.error(response.message);
                        
                    }
                },
                error: function(xhr, status, error) {
                    
                    toastr.error('Error occurred while processing the request.');
                    
                },
                cache: false,
                processData: false,
                contentType: false,
                timeout: 5000
            });
        });
    });

    
</script>

@endsection 
