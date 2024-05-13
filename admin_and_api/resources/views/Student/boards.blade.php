
@foreach ($boards as $board)
<div class="col-xl-3 col-lg-6 col-12 cursor-pointer ">
    <div class="card pull-up subject-div" exam_id="{{$examId ?? ''}}" subject_id="{{$subjectId ?? ''}}" board_id="{{$board->id ?? ''}}">
        <div class="card-content">
            <div class="card-body">
                <div class="media d-flex">
                    <div class="media-body text-left">
                        <h3 class="info text-center subject-name">{{$board->board_name ?? ''}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
