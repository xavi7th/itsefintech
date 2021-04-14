@extends('layouts.master')

@section('contents')
  <section id="home" class="min-h-70vh">
    <div class="mx-5 md-mx-l5 banner">
      <div class="banner-content">

        <div class="items-center md-w-100pc p-10 text-center">
          <img src="./assets/images/piggy1.png" style="height: 300px;">
          <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Product Sans Regular;"> Save Better,</h1>
          <div class="billboard" style="margin-top: -45px;">
            <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Product Sans Regular;">Earn More! </h1>

          </div>
          <p class="white opacity-80 fw-300">Get up to 24% per annum in lifestlye rewards and cash benefits when you save. Let your money work fo you.</p>
          <div>
            <a href="#" class="button bg-red white fw-600 no-underline mx-5">Coming soon on web.</a><br><a href="https://play.google.com/store/apps/details?id=com.doublesouth.victor_app"><img src="{{ asset('basicsite/images/playstore.png') }}" style="height: 60px;"></a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
