@if (count($all_discussions) > 0)
    @foreach ($all_discussions as $key => $discussion)
        @php
            $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $discussion->created_at)->format('Y-m-d');
            if ($created_at == date('Y-m-d')) {
                $created_dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $discussion->created_at)->format('g:i A');
            } else {
                $created_dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $discussion->created_at)->format('Y-m-d g:i A');
            }
        @endphp
        @if ($discussion->msg_by_type == 1)
            <li class="media">
                <div class="media-left">
                    <a href="{{ asset('backend/assets/images/placeholder.jpg') }}">
                        <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" class="img-circle" alt="">
                    </a>
                </div>

                <div class="media-body">
                    <div class="media-content">{{$discussion->message}}</div>
                    <span class="media-annotation display-block mt-10">{{$created_dateTime}}</span>
                </div>
            </li>
        @elseif($discussion->msg_by_type == 2)
            <li class="media reversed">
                <div class="media-body">
                    <div class="media-content">{{$discussion->message}}</div>
                    <span class="media-annotation display-block mt-10">{{$created_dateTime}}</span>
                </div>

                <div class="media-right">
                    <a href="{{ asset('backend/assets/images/placeholder.jpg') }}">
                        <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" class="img-circle" alt="">
                    </a>
                </div>
            </li>
        @endif
    @endforeach
@else
    <ul class="media-list chat-list content-group">
        <li class="media date-step content-divider">
            <span>No Discussion Found!!!</span>
        </li>
    </ul>
@endif