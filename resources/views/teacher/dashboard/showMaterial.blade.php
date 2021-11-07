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
<div class="panel panel-flat">
    <div class="panel-body">
        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
                <tr>
                    <th width="5%">SL</th>
                    <th width="25%">Video Title</th>
                    <th width="70%">Iframe</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($class_materials))
                     @foreach($class_materials as $key => $material)
				  	 	<tr>
				  	 		<td>{{$key+1}}</td>
				  	 		<td>
                                <a href="https://www.youtube.com/watch?v={{$material->video_id}}" target="_blank" class="letter-icon-title">{{ $material->video_title }}</a>
                            </td>
				  	 		<td>
                                <div class="youtube" data-embed="{{$material->video_id}}"> 
                                    <div class="play-button"></div> 
                                </div>
                                {{-- <iframe width="400" height="200" src="https://www.youtube.com/embed/{{$material->video_id}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>   --}}
                            </td>
				  	 	</tr>
				    @endforeach
                @else
                    <tr>
                        <td colspan="4">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

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
            iframe.setAttribute( "width", "400" );
            iframe.setAttribute( "loading", "lazy" );
            iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ this.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );

            this.innerHTML = "";
            this.appendChild( iframe );
        });
    }

</script>