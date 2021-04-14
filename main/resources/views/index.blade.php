@extends('layouts.master')

@section('contents')


    <section id="home" class="min-h-70vh">
      <div class="mx-5 md-mx-l5 banner">
        <div class="banner-content">
          <div class="banner-left">
            <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Product Sans Regular;"> Personalized</h1>
            <div class="billboard" style="margin-top: -45px;">
              <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Product Sans Regular;">Convenience </h1>
            </div>

            <p class="white fw-300 fs-9 opacity-50" style="font-family: Product Sans Regular;"> We are reimagining the way you spend, borrow and pay bills.
              <br> We are not just a bank, we are your lifestyle partner.
            </p>

            <div class="">
              <a href="https://play.google.com/store/apps/details?id=com.doublesouth.victor_app"><img style="height: 60px;" src="{{ asset('basicsite/images/playstore.png') }}"></a>

            </div>

          </div>

          <div class="img-right">
            <img src="{{ asset('basicsite/images/cx-web1.png') }}">
          </div>
        </div>
      </div>
    </section>



    <section id="features" class="p-10 md-p-l5">


      <h1 class="white fs-l3 lh-2 md-fs-xl1 md-lh-1 fw-900 my-10" style="font-family: Nocturnal Demo;">..the future is a better lifestyle</h1>

      <div class="flex flex-column md-flex-row mx-auto">
        <div class="w-100pc md-w-40pc">
          <div class="br-8 p-5 m-5">
            <div class="flex justify-center items-center white w-l5 h-l5 br-round mb-5">
              <div class="icons">
                <img src="{{ asset('basicsite/icon/1.svg') }}">
              </div>
            </div>
            <h4 class="white fw-600 fs-m3 mb-5" style="font-family: Product Sans Regular;">Spend Better</h4>
            <div class="red-lightest fw-300 fs-9 opacity-50" style="font-family: Product Sans Regular;">The Capital X card gives you exceptional spending power. Shop via POS, web and make free ATM withdrawals.</div>

          </div>
        </div>
        <div class="w-100pc md-w-40pc">
          <div class="br-8 p-5 m-5">
            <div class="flex justify-center items-center white w-l5 h-l5 br-round mb-5">
              <div class="icons">
                <img src="{{ asset('basicsite/icon/4.svg') }}">
              </div>
            </div>
            <h4 class="white fw-600 fs-m3 mb-5" style="font-family: Product Sans Regular;">Rewards</h4>
            <div class="red-lightest fw-300 fs-9 opacity-50" style="font-family: Product Sans Regular;">Get rewards personalized to your lifestyle. We understand that you're unique so tailored your benefits to fit you.</div>

          </div>
        </div>
        <div class="w-100pc md-w-40pc">
          <div class="br-8 p-5 m-5">
            <div class="flex justify-center items-center white w-l5 h-l5 br-round mb-5">
              <div class="icons">
                <img src="{{ asset('basicsite/icon/3.svg') }}">
              </div>
            </div>
            <h4 class="white fw-600 fs-m3 mb-5" style="font-family: Product Sans Regular;">Borrow</h4>
            <div class="red-lightest fw-300 fs-9 opacity-50" style="font-family: Product Sans Regular;">Buy what you need, when you need it.. Get access to credit so you don’t need to put your life on hold!</div>

          </div>
        </div>
        <div class="w-100pc md-w-40pc">
          <div class="br-8 p-5 m-5">
            <div class="flex justify-center items-center white w-l5 h-l5 br-round mb-5">
              <div class="icons">
                <img src="{{ asset('basicsite/icon/5.svg') }}">
              </div>
            </div>
            <h4 class="white fw-600 fs-m3 mb-5" style="font-family: Product Sans Regular;">Savings</h4>
            <div class="red-lightest fw-300 fs-9 opacity-50" style="font-family: Product Sans Regular;">With Capital X, you save and get returns up to 12% pa. It doesn't end there, you also get rewarded with lifestyle benefits.</div>

          </div>
        </div>
      </div>
    </section>

    <section class="p-10 md-py-10">
      <div class="w-100pc md-w-70pc mx-auto py-10">
        <h2 class="white fs-l2 md-fs-xl1 fw-900 lh-2" style="font-family: Product Sans Regular;">
          Premium experience that befits your <span class="border-b bc-red bw-4 fw-1200 1h-2 fs-16" style="font-family: Nocturnal Demo;"> lifestyle</span> with a stylishly crafted card for you. </h2>
      </div>
    </section>

    <section class="p-0 md-p-5">
      <div class="flex flex-wrap">
        <div class="w-100pc md-w-33pc p-10">
          <a href="cards.html" class="block no-underline p-5 br-8 hover-bg-red-lightest-10 hover-scale-up-1 ease-300">
            <img class="w-100pc" src="{{ asset('basicsite/images/card1.jpg') }}" alt="">
            <p class="fw-600 white fs-m3 mt-3">
              Titanium Black
            </p>
            <p style="color: white;">Power in your hands, the world is yours.</p>
            <div class="red fs-s3 italic after-arrow-right my-4">read more</div>
          </a>
        </div>
        <div class="w-100pc md-w-33pc p-10">
          <a href="cards.html" class="block no-underline p-5 br-8 hover-bg-red-lightest-10 hover-scale-up-1 ease-300">
            <img class="w-100pc" src="{{ asset('basicsite/images/card2.jpg') }}" alt="">
            <p class="fw-600 white fs-m3 mt-3">
              Titanium Pro
            </p>
            <p style="color: white;">Do more, create better</p>
            <div class="red fs-s3 italic after-arrow-right my-4">read more</div>
          </a>
        </div>
        <div class="w-100pc md-w-33pc p-10">
          <a href="cards.html" class="block no-underline p-5 br-8 hover-bg-red-lightest-10 hover-scale-up-1 ease-300">
            <img class="w-100pc" src="{{ asset('basicsite/images/card3.jpg') }}" alt="">
            <p class="fw-600 white fs-m3 mt-3">
              Titanium Merchant
            </p>
            <p style="color: white;">Stay loyal, live better.</p>
            <div class="red fs-s3 italic after-arrow-right my-4">read more</div>
          </a>
        </div>

      </div>
    </section>

    <section id="premium">
      <div class="p-10 flex flex-wrap">
        <div class="w-100pc md-w-50pc">
          <div class="p-5">
            <h4 class="white fw-800 fs-l3 mb-5">Premium Banking</h4>
            <p class="red-lightest fw-300 fs-m1 opacity-50">Subscribe to get access to exclusive convenience, more rewards and premium access to the best places in the world.</p>
            <a href="https://play.google.com/store/apps/details?id=com.doublesouth.victor_app"><img style="height: 60px;" src="{{ asset('basicsite/images/playstore.png') }}"></a>
            <h4 class="white fw-600 fs-m2 mt-10">Companies trusts us.</h4>
            <br>
            <div class="flex red-lightest opacity-50">
              <div class="w-25pc">
                <img src="{{ asset('basicsite/images/access.png') }}">

              </div>
              <div class="w-25pc">
                <img src="{{ asset('basicsite/images/providus.png') }}">
              </div>
              <div class="w-25pc">
                <img src="{{ asset('basicsite/images/wallets.png') }}">

              </div>
            </div>
          </div>
        </div>
        <div class="w-100pc md-w-25pc">
          <div class="m-3 p-5 br-8 bg-red-lightest-10 overflow-hidden">
            <div class="p-3">
              <h3 class="red">Basic</h3>
              <div class="white flex items-center">N<span class="fs-l5 lh-1">0</span>/mo</div>
            </div>
            <div class="p-3 red-lightest fw-400 fs-s1 lh-5">
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Up to N250,000</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Personalized Rewards</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Up to 5% Cashbacks</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">24x7 support</span>
              </div>
              <div>
                <i class="h-3" stroke-width="4" data-feather="x"></i>
                <span class="opacity-50">Free Bank Transfers</span>
              </div>
            </div>
            <div class="p-3" href="">
              <a href="#top"> <button class="button full bg-black white  hover-opacity-100 hover-scale-up-1 ease-300">GET</button></a>
            </div>
          </div>
        </div>
        <div class="w-100pc md-w-25pc">
          <div class="m-3 p-5 br-8 bg-white overflow-hidden">
            <div class="p-3">
              <h3 class="red">Premium</h3>
              <div class="black flex items-center">N<span class="fs-l5 lh-1">1000</span>/mo</div>
            </div>
            <div class="p-3 black fw-400 fs-s1 lh-5">
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50"> 2 Cards</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Unlimited Transfers</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Up to 15% Cashbacks</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Dedicated Support</span>
              </div>
              <div>
                <i class="h-3 red" stroke-width="4" data-feather="check"></i>
                <span class="opacity-50">Exclusive Deals & Discounts</span>
              </div>
            </div>
            <div class="p-3">
              <a target="_blank" href="https://paystack.com/pay/upg06q9a4r"><button class="button full bg-red white hover-opacity-100 hover-scale-up-1 ease-300">Subscribe</button></a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="p-10 md-p-l10">
      <div class="md-w-75pc">
        <h2 class="white fs-l3 fw-900 lh-1"><span class="red">Employed?</span> Get your paycheck early without charges.</h2>
        <p class="red-lightest fw-300 fs-m1 opacity-50 my-5">We get it. Get your paycheck early! That’s seven more days to do more with your money! No extra charges, no interest. Join premium now to get started.</p>
      </div>
      <div class="relative w-100pc h-75vh bg-cover bg-b" style="background-image: url({{ asset('basicsite/images/cx5.jpg') }});">
        <a href="#premium" class="bg-white black px-5 py-3 absolute right-0 bottom-0 hover-bg-black hover-white ease-500 flex justify-center items-center after-arrow-right no-underline fs-m1"></a>
      </div>

    </section>



    <section class="p-10 md-p-l5" id="testimonials">

      <div class="my-10">

        <h4 class="white  fs-l3 lh-2 lh-1 fw-900"><span class="border-b bc-red bw-4 fw-1200 1h-2 fs-16">Testimonials</span></h4>
        <p class="red-lightest fw-300 fs-m1 opacity-50">Here's what some of our users are saying.</p>
      </div>

      <div id="slider-2">
        <div class="px-3">
          <div class="p-8 br-8 bg-red-lightest-10 relative">
            <span class="fs-l5 lh-1 white opacity-10 absolute top-0 z--1">&#10077;</span>
            <p class="fw-600 fs-m1 red-lightest opacity-80 italic ls-wider">A good banking app with user friendly features and support.</p>
            <div class="flex items-center my-5">
              <div class="block br-round border bc-black bw-4 w-l3 h-l3"><img class="br-round" src="{{ asset('basicsite/images/ggle.jpg') }}" alt=""></div>
              <div class="ml-4 fs-s1">
                <p class="red-lightest">Effiri</p>
                <div class="red-lightest opacity-20">Playstore review</div>
              </div>
            </div>
          </div>
        </div>
        <div class="px-3">
          <div class="p-8 br-8 bg-red-lightest-10 relative">
            <span class="fs-l5 lh-1 white opacity-10 absolute top-0 z--1">&#10077;</span>
            <p class="fw-600 fs-m1 red-lightest opacity-80 italic ls-wider">I was given a credit of 50k & I paid back early without interest. </p>
            <div class="flex items-center my-5">
              <div class="block br-round border bc-black bw-4 w-l3 h-l3"><img class="br-round" src="{{ asset('basicsite/images/ggle.jpg') }}" alt=""></div>
              <div class="ml-4 fs-s1">
                <p class="red-lightest">Tshola</p>
                <div class="red-lightest opacity-20">Playstore review</div>
              </div>
            </div>
          </div>
        </div>
        <div class="px-3">
          <div class="p-8 br-8 bg-red-lightest-10 relative">
            <span class="fs-l5 lh-1 white opacity-10 absolute top-0 z--1">&#10077;</span>
            <p class="fw-600 fs-m1 red-lightest opacity-80 italic ls-wider">Beautiful card, amazing team, seamless transactions.</p>
            <div class="flex items-center my-5">
              <div class="block br-round border bc-black bw-4 w-l3 h-l3"><img class="br-round" src="{{ asset('basicsite/images/fb.svg') }}" alt=""></div>
              <div class="ml-4 fs-s1">
                <p class="red-lightest">Kehinde</p>
                <div class="red-lightest opacity-20">Facebook review</div>
              </div>
            </div>
          </div>
        </div>
        <div class="px-3">
          <div class="p-8 br-8 bg-red-lightest-10 relative">
            <span class="fs-l5 lh-1 white opacity-10 absolute top-0 z--1">&#10077;</span>
            <p class="fw-600 fs-m1 red-lightest opacity-80 italic ls-wider">First time I'm trying premium banking. Thanks Capital X team.</p>
            <div class="flex items-center my-5">
              <div class="block br-round border bc-black bw-4 w-l3 h-l3"><img class="br-round" src="{{ asset('basicsite/images/ig.jpg') }}" alt=""></div>
              <div class="ml-4 fs-s1">
                <p class="red-lightest">Victor</p>
                <div class="red-lightest opacity-20">Instagram review</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <section class="p-10 md-p-l5" id="faq">

      <h4 class="white fs-l3 fw-900"><span class="border-b bc-red bw-4 fw-1200 1h-2 fs-16">FAQs</span></h4>
      <p class="red-lightest fw-300 fs-m1 opacity-50">Common questions asked.</p>
      <br>



      <div class="accordion accordion-gap w-100pc md-w-75pc" id="accordionFAQ">
        <div class="accordion-item wow fadeInRight">
          <div class="accordion-trigger" id="headingFour">
            <div class="accordion-item wow fadeInRight">
              <div class="accordion-trigger" id="headingFive">
                <button class="btn fw-500 fs-m2" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1"><span style="font-family: Product Sans Regular;">Where can I use my Capital X card?</span></button>
              </div>
              <div id="collapse1" class="collapse show" aria-labelledby="headingFive" data-parent="#accordionFAQ">
                <div class="accordion-content">
                  <p>Our cards work anywhere Verve and Visa are accepted.</p>
                </div>
              </div>
            </div>
            <div class="accordion-item wow fadeInRight">
              <div class="accordion-trigger" id="headingSix">
                <button class="btn collapsed fw-500 fs-m2" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3"><span style="font-family: Product Sans Regular;">How safe and secured is my money?</span></button>
              </div>
              <div id="collapse3" class="collapse" aria-labelledby="headingSix" data-parent="#accordionFAQ">
                <div class="accordion-content">
                  <p>All funds are domiciled with our banking partner, Providus Bank who're licensed and regulated by CBN</p>
                </div>
              </div>
            </div>
            <div class="accordion-item wow fadeInRight">
              <div class="accordion-trigger" id="headingSeven">
                <button class="btn collapsed fw-500 fs-m2" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4"><span style="font-family: Product Sans Regular;">Can I borrow on Capiatl X?</span></button>
              </div>
              <div id="collapse4" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionFAQ">
                <div class="accordion-content">
                  <p>Yes! We offer short term credits to our users up to N250,000.</p>
                </div>
              </div>
            </div>
            <div class="accordion-item wow fadeInRight">
              <div class="accordion-trigger" id="headingSeven">
                <button class="btn collapsed fw-500 fs-m2" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4"><span style="font-family: Product Sans Regular;">Can I save on Capiatl X?</span></button>
              </div>
              <div id="collapse4" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionFAQ">
                <div class="accordion-content">
                  <p>Yes! You can save and get up to 12% pa in returns.</p>
                </div>
              </div>
            </div>

            <div class="accordion-item wow fadeInRight">
              <div class="accordion-trigger" id="headingEight">
                <button class="btn collapsed fw-500 fs-m2" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5"><span style="font-family: Product Sans Regular;">How do I get rewards?</span></button>
              </div>
              <div id="collapse5" class="collapse" aria-labelledby="headingEight" data-parent="#accordionFAQ">
                <div class="accordion-content">
                  <p>We will reward you for everytime you spend. We go a step further by personalizing your rewards to fit your lifestyle</p>

                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

@endsection
