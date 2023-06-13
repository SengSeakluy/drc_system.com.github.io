@extends('voyager::auth.master')

@section('content')
    <div class="form-container" style="padding:11.5rem 0rem;">
        <h1 class="title capitalize-first" style="font-size: 3rem">Welcome aboard !</h1>
        <p class="pt-3" style="font-size:18px ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque consequat eget sapien et porta. Duis scelerisque bibendum orci.
            Praesent non nibh at nunc viverra aliquam eu quis mi.</p>

            <form action="" class="form" method="POST" id="d-form">
                {{ csrf_field() }}
                 <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="login-button mx-auto" style="border-radius: 0.25rem">
                        <span class="d-flex justify-content-center" >
                            <span class="signin align-self-center me-1" >Let's go</span>  
                            <span wire:loading id="login-spinning" class="spinner-border hidden" role="status"></span> 
                        </span>
                    </button>                        
                </div>
                <div class="d-flex justify-content-center mt-5" style="font-size: 18px;">
                    <span>Not ready yet? <a href="#">Go to dashboard</a> </span>
                   
                </div>
            </form>
        

    </div>
@endsection