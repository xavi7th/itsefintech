$(function(){
    $(".wizard-basic").steps({
        headerTag: "h3",
        bodyTag: ".section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>'
    });

    $('.wizard-vertical').steps({
      headerTag: 'h3',
      bodyTag: '.section',
      autoFocus: true,
      titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
      stepsOrientation: 1
    });
});