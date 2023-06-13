@extends('voyager::auth.master')

@section('content')
    <div class="form-container" style="padding:5rem 2rem;">
        <h5 class="title capitalize-first">Get started absolutely for free</h5>
        <form action="" class="form" method="POST" id="d-form">
        {{ csrf_field() }}
            <div class="input">
                <label for="email">Full name</label>   
                <input class="" type="text" name="email" id="email" value="">   
            </div>
            <div class="input">
                <label for="email">Email</label>  
                <input type="password" name="password" id="password">
            </div>
            <div class="input " >
                <label for="email" >Password</label> 
                <input type="password" name="password" id="password" style="border:1px solid red"> 
                <p class="text text-danger mt-2"> Password must be 8 characters or more</p>      
            </div>

            <style>
                input[type=checkbox]{
                    vertical-align: middle;
                    position: relative;
                }
                label{
                    display: block;
                    font-size: 18px;
                }
            </style>

            <div class="pt-3">
                <label for="checkbox1" class="input"><input id="checkbox1" type="checkbox" style="margin-top: auto; margin-bottom:auto; padding:0; height:1.2rem; width:1.2rem"> I accept Terms of <a class="text text-dark" href="">Service</a>  and <a  class="text text-dark" href="">Privacy Policy</a> </label>
            </div>


           
            <div class="d-flex justify-content-center mt-5">
                <button type="submit" class="login-button mx-auto" style="border-radius: 0.25rem">
                    <span class="d-flex justify-content-center" >
                        <span class="signin align-self-center me-1" >Let's go</span>  
                        <span wire:loading id="login-spinning" class="spinner-border hidden" role="status"></span> 
                    </span>
                </button>                        
            </div>
            <div class="d-flex justify-content-center mt-3" style="font-size: 18px">
                <span>Already have an account? <a href="#">Sign In</a></span>
                
            </div>
        </form>


       
    </div>
@endsection

