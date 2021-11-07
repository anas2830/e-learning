<style>
    .youtube {
        background-color: #000;
        position: relative;
        padding-top: 48.25%;
        overflow: hidden;
        cursor: pointer;
    }
    .youtube img {
        width: 100%;
        top: -8.84%;
        left: 0;
        opacity: 0.7;
    }
    .youtube .play-button {
        width: 90px;
        height: 60px;
        background-color: #333;
        box-shadow: 0 0 30px rgba( 0,0,0,0.6 );
        z-index: 1;
        opacity: 0.8;
        border-radius: 6px;
    }
    .youtube .play-button:before {
        content: "";
        border-style: solid;
        border-width: 15px 0 15px 26.0px;
        border-color: transparent transparent transparent #fff;
    }
    .youtube img,
    .youtube .play-button {
        cursor: pointer;
    }
    .youtube img,
    .youtube iframe,
    .youtube .play-button,
    .youtube .play-button:before {
        position: absolute;
    }
    .youtube .play-button,
    .youtube .play-button:before {
        top: 50%;
        left: 50%;
        transform: translate3d( -50%, -50%, 0 );
    }
    .youtube iframe {
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
    }
</style>
<!-- Simple panel -->
<div class="panel panel-white">

    <input type="hidden" id="assign_batch_class_id" value="{{$assign_batch_class_id}}" />
    <div class="panel-body">
        @if (count($class_lecture_links) > 0)
            @foreach ($class_lecture_links as $link)
                <div class="youtube" data-embed="{{$link->video_id}}"> 
                    <div class="play-button"></div> 
                </div>
                <br><br>
            @endforeach
        @else
            <p class="content-group">
                There has no Class Videos!!!
            </p>
        @endif
    </div>
</div>
<!-- /simple panel -->

<script type="text/javascript">

    var youtube = document.querySelectorAll( ".youtube" );
    for (var i = 0; i < youtube.length; i++) {
        var source = "https://img.youtube.com/vi/"+ youtube[i].dataset.embed +"/mqdefault.jpg"; 

        var image = new Image();
        image.src = source;
        image.addEventListener( "load", function() {
            youtube[ i ].appendChild( image );
        }( i ) );

        youtube[i].addEventListener( "click", function() {
            var iframe = document.createElement( "iframe" );
            iframe.setAttribute( "frameborder", "0" );
            iframe.setAttribute( "allowfullscreen", "" );
            iframe.setAttribute( "height", "600" );
            iframe.setAttribute( "width", "989" );
            iframe.setAttribute( "loading", "lazy" );
            iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ this.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );

            this.innerHTML = "";
            this.appendChild( iframe );
        });
    }

</script>