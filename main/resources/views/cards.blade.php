@extends('layouts.master')

@section('contents')

  <section id="home" class="min-h-70vh">
    <div class="mx-5 md-mx-l5 banner">
      <div class="banner-content">

        <div class="banner-left">
          <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Product Sans Regular;"> Stylishly Crafted</h1>
          <div class="billboard" style="margin-top: -45px;">
            <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Product Sans Regular;">Cards For You </h1>
          </div>


          <p class="white fw-300 fs-9 opacity-50" style="font-family: Product Sans Regular;"> Our cards gives you the freedom you deserve. Spend better, borrow cheaper and earn more.
          </p>

          <div class="">
            <a href=""><img style="height: 60px;" src="{{ asset('basicsite/images/playstore.png') }}"></a>

          </div>

        </div>

        <div class="img-right">
          <img src="{{ asset('basicsite/images/cx4.png') }}" style="height: 100%">
        </div>
      </div>

    </div>
  </section>

  <section class="p-0 md-p-5">
    <div class="flex flex-wrap">
      <div class="w-100pc md-w-33pc p-10">
        <a href="#" class="block no-underline p-5 br-8 hover-bg-red-lightest-10 hover-scale-up-1 ease-300">
          <img class="w-100pc p-10" src="{{ asset('basicsite/images/c2.png') }}" alt="">
          <p class="fw-600 white fs-m3 mt-3 text-center">
            Titanium Black <br><span class="white fs-m1 fw-100 opacity-50">Extra Convenience</span>
            <br><span class="fs-l5 lh-1 red">3,000</span>
          </p>
          <div class="p-3 red-lightest fw-400 fs-s1 lh-5 text-center">
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Up to N100,000 Credit</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Personalized Rewards</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Up to 10% Cashbacks</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">24x7 support</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">10 Free Bank Transfers</span>
            </div>
          </div>
        </a>

      </div>
      <div class="w-100pc md-w-33pc p-10">
        <a href="#" class="block no-underline p-5 br-8 hover-bg-red-lightest-10 hover-scale-up-1 ease-300">
          <img class="w-100pc p-10" src="{{ asset('basicsite/images/c3.png') }}" alt="">
          <p class="fw-600 white fs-m3 mt-3 text-center">
            Titanium Pro <br><span class="white fs-m1 fw-100 opacity-50">Premium Lifestyle</span>
            <br><span class="fs-l5 lh-1 red">5,000</span>
          </p>
          <div class="p-3 red-lightest fw-400 fs-s1 lh-5 text-center">
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Up to N250,000 Credit</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Personalized Rewards</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Up to 15% Cashbacks</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">24x7 support</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">25 Free Bank Transfers</span>
            </div>
          </div>
        </a>

      </div>
      <div class="w-100pc md-w-33pc p-10">
        <a href="#" class="block no-underline p-5 br-8 hover-bg-red-lightest-10 hover-scale-up-1 ease-300">
          <img class="w-100pc p-10" src="{{ asset('basicsite/images/c4.png') }}" alt="">
          <p class="fw-600 white fs-m3 mt-3 text-center">
            Titanium Merchant <br><span class="white fs-m1 fw-100 opacity-50">More rewards.</span>
            <br><span class="fs-l5 lh-1 red">1,000</span>
          </p>
          <div class="p-3 red-lightest fw-400 fs-s1 lh-5 text-center">
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Up to N50,000 Credit</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Personalized Rewards</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">Up to 25% Cashbacks</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">24x7 support</span>
            </div>
            <div>
              <i class="h-3 red" stroke-width="4" data-feather="check"></i>
              <span class="opacity-50">25 Free Bank Transfers</span>
            </div>
          </div>

        </a>

      </div>

    </div>
  </section>

@endsection
