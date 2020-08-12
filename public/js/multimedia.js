$(function(){
    $('.trigger-audio, .image.trigger').click(function(e){
        e.preventDefault();
        $('.audio').addClass('d-none');
        $('.title').show();
        $('.breadcrumbs').show();
        $('.trigger-audio').show();
        $('.trigger-video').show();
        var header = $(this).parents('.header');
        header.find('.title').hide();
        header.find('.breadcrumbs').hide();
        header.find('.trigger-audio').hide();
        header.find('.trigger-video').hide();
        header.find('.audio').removeClass('d-none');
        header.find('audio')[0].player.play();
    });
    $('.trigger-audio-article3, .image.trigger-article3').click(function(e){
        e.preventDefault();
        $('.audio').addClass('d-none');
        $('.title').show();
        $('.breadcrumbs').show();
        $('.trigger-audio-article3').show();
        $('.trigger-video').show();
        var header = $(this).parents('.header');
        //header.find('.title').hide();
        header.find('.breadcrumbs').hide();
        header.find('.trigger-audio-article3').hide();
        header.find('.trigger-video').hide();
        header.find('.audio').removeClass('d-none');
        header.find('audio')[0].player.play();
    });
    $('.trigger-audio-in-sections').click(function(e){
        e.preventDefault();
        $('.control.audio').addClass('d-none');
        $('.trigger-audio-in-sections').show();
        $('.trigger-video-in-sections').show();

        var controls = $(this).parents('.controls');
        controls.find('.trigger-audio-in-sections').hide();
        controls.find('.trigger-video-in-sections').hide();
        controls.parents('.art').find('.control.audio').removeClass('d-none');
        controls.parents('.art').find('audio')[0].player.play();
        
    });
    $('.trigger-image-in-sections').click(function(e){
        e.preventDefault();
        $('.control.audio').addClass('d-none');
        $('.trigger-audio-in-sections').show();
        $('.trigger-video-in-sections').show();

        var controls = $(this).parents('.art').find('.controls');
        controls.find('.trigger-audio-in-sections').hide();
        controls.find('.trigger-video-in-sections').hide();
        controls.parents('.art').find('.control.audio').removeClass('d-none');
        controls.parents('.art').find('audio')[0].player.play();
        
    });
    $('.audio button').on('click', function (e) {
        var header = $(this).parents('.header');
        header.find('.audio').addClass('d-none');
        header.find('.title').show();
        header.find('.breadcrumbs').show();
        header.find('.trigger-audio').show();
        header.find('.trigger-audio-article3').show();
        header.find('.trigger-video').show();
    });
    $('.audio.sections button').on('click', function (e) {
        var controls = $(this).parents('.art').find('.controls');
        controls.find('.trigger-audio-in-sections').show();
        controls.find('.trigger-video-in-sections').show();
        controls.parents('.art').find('.control.audio').addClass('d-none');
        
        
    });
    $('#videoModal')
        .on('show.bs.modal', function (e) {
            var trigger = $(e.relatedTarget);
            $('.control.audio').addClass('d-none');
            $('.trigger-audio-in-sections').show();
            $('.trigger-video-in-sections').show();
            $('.audio').addClass('d-none');
            $('.title').show();
            $('.breadcrumbs').show();
            $('.trigger-audio').show();
            $('.trigger-video').show();
            var title = trigger.parents('.article').data('title');
            if(!title) title = trigger.parents('.article-long').data('title') 
            var video_src = trigger.data('location')
            $(this).find('.data-title').text(title);
            $(this).find('video').mediaelementplayer();
            $(this).find('video')[0].player.setSrc(video_src)
            //alert($(this).find('video')[0].player.html())
            $(this).find('video')[0].player.play();
        })
        .on('hide.bs.modal', function (e) {
            $(this).find('video')[0].player.pause();
        });

    $('.multimedia-block').find('div.c').click(function(e){
        $(this).fadeOut(400, function () {
            var b = $(this).parents('li');
            b.siblings('li').removeClass('selected');
            var a = $(this).parents('ul');
            a.prepend(b.detach());
            b.addClass('selected');
            $(this).fadeIn(400);
        });
        var screen = $('#screen');
        screen.html($('#loader').clone().removeAttr('id').removeClass('d-none'));
        $.get('/ajax/media', {'type':$(this).data('type'), 'id':$(this).data('id')}, function (response) {
            screen.html(response);
        });
    });
});