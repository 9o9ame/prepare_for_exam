{{-- @if (isset($Subjects))
    @foreach ($Subjects as $Subject)
    <label for=""> {{$Subject->subject_name ?? ''}}</label>
    <select class="form-control">
        @foreach ($Subject->boards as $board)
        <option value="{{$board->id ?? ''}}">{{$board->board_name ?? ''}}</option>
        @endforeach
    </select>
    @endforeach
@endif --}}
<div classname="card">
    <div classname="card-content">
        <div classname="card-body">
            @if (isset($Subjects))
                @foreach ($Subjects as $Subject)
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed"
                                    type="button" data-toggle="collapse" data-target="#collapse-{{$Subject->id ?? ''}}"
                                    aria-expanded="false" aria-controls="collapseTwo">{{$Subject->subject_name ?? ''}}</button></h2>
                            <div id="collapse-{{$Subject->id ?? ''}}" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                data-parent="#accordionExample" style="">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach ($Subject->boards as $board)
                                        <div class="col-md-3 mob-view">
                                            <form action="{{route('fetch-topics')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="exam_id" id="" value="{{$exam_id ?? ''}}">
                                                <input type="hidden" name="subject_id" id="" value="{{$Subject->id ?? ''}}">
                                                <input type="hidden" name="board_id" id="" value="{{$board->id ?? ''}}">
                                                <button type="submit"
                                                        class="btn btn-danger btn-min-width ">{{$board->board_name ?? ''}}</button>
                                                    </div>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
