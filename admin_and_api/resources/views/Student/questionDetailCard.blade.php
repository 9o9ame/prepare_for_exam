<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('fetch-question') }}" method="post" id="fetch-question">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $request->subject_id ?? '' }}">
                        <input type="hidden" name="exam_id" value="{{ $request->exam_id ?? '' }}">
                        <input type="hidden" name="board_id" value="{{ $request->board_id ?? '' }}">
                        <input type="hidden" name="topic" value="{{ $request->topic ?? '' }}">
                        <input type="hidden" name="subtopic" value="{{ $request->subtopic ?? '' }}">
                        <input type="hidden" name="question_type" value="{{ $request->question_type ?? '' }}">
                        <button type="submit" class="btn btn-warning fetch-question-sub">View All Questions</button>
                    </form>
                </div>
                <div class="col-md-6 d-flex justify-content-end"><button type="button"
                        class="btn btn-success">{{ $question->subject_code ?? '' }} {{ $question->year ?? '' }}
                        ({{ $question->mark ?? '' }} Marks)</button></div>
            </div>
            <div class="row mt-2">
                <div class="col-xl-12 col-md-6 col-12">
                    <div class="card bg-infos">
                        <div class="card-content">
                            <div class="card-body pb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-black mb-1"><i class="cc XRP" title="XRP"></i>Question</h4>
                                        <h6 class="text-black">
                                            <p>{!! $question->question ?? '' !!}</p>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12 mx-auto">
                            <textarea id="userinput8" rows="6" class="form-control border-primary" name="bio" placeholder="">{!! $question->notes->question_notes ?? '' !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-2 col-defined">
                    <form method="post" class="question-detail-form">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $request->subject_id ?? '' }}">
                        <input type="hidden" name="exam_id" value="{{ $request->exam_id ?? '' }}">
                        <input type="hidden" name="board_id" value="{{ $request->board_id ?? '' }}">
                        <input type="hidden" name="topic" value="{{ $request->topic ?? '' }}">
                        <input type="hidden" name="subtopic" value="{{ $request->subtopic ?? '' }}">
                        <input type="hidden" name="question_type" value="{{ $request->question_type ?? '' }}">
                        <input type="hidden" name="question_id" value="{{ $question->id ?? '' }}">
                        <input type="hidden" name="question_action" value="previous">
                            <button type="button" class="text-capitalize  btn btn-outline-info question-detail">
                                Previous</button>
                    </form>
                    </div>
                <div class="col-md-2 col-defined">
                    <button type="button"
                        class="text-capitalize complete  btn @if (isset($question->reads) && $question->reads->type == 'complete') bg-success text-white @endif  btn-outline-success  mark-as"
                        url="{{ route('mark-as', [$question->id, 'complete']) }}"> Mark as Complete</button>
                    <div class="spinner-border text-success d-none complete-spinner" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="col-md-2 col-defined"><button class="text-capitalize  btn btn-outline-success mark-scheme">
                        <span class="show-hide">Show</span>
                        Mark-scheme</button></div>
                <div class="col-md-2 col-defined"><button type="button"
                        class="text-capitalize  btn btn-outline-success update-notes" q_id="{{ $question->id ?? '' }}">
                        Update Notes</button>
                    <div class="spinner-border text-success d-none update-spinner" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="col-md-2 col-defined"> <button type="button"
                        class="text-capitalize revisit btn @if (isset($question->reads) && $question->reads->type == 'revisit') bg-danger text-white @endif btn-outline-danger  mark-as"
                        url="{{ route('mark-as', [$question->id, 'revisit']) }}"> Mark Revisit</button>
                    <div class="spinner-border text-danger d-none revisit-spinner" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="col-md-2 col-defined">
                    <form method="post" class="question-detail-form">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $request->subject_id ?? '' }}">
                        <input type="hidden" name="exam_id" value="{{ $request->exam_id ?? '' }}">
                        <input type="hidden" name="board_id" value="{{ $request->board_id ?? '' }}">
                        <input type="hidden" name="topic" value="{{ $request->topic ?? '' }}">
                        <input type="hidden" name="subtopic" value="{{ $request->subtopic ?? '' }}">
                        <input type="hidden" name="question_type" value="{{ $request->question_type ?? '' }}">
                        <input type="hidden" name="question_id" value="{{ $question->id ?? '' }}">
                        <input type="hidden" name="question_action" value="next">
                        <button type="button" class="text-capitalize  btn btn-outline-info question-detail">
                            Next</button>
                    </form>
                </div>
            </div>
            <div class="row mt-4 mark-scheme-content d-none">
                <div class="col-xl-12 col-md-6 col-12">
                    <div class="card bg-infos">
                        <div class="card-content">
                            <div class="card-body pb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-black">
                                            <p>{!! $question->mark_schema ?? '' !!}</p>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="justify-content: center; display: flex;"><button
                        class="btn btn-danger mark-scheme">Hide Mark-scheme</button></div>
            </div>
        </div>
    </div>
</div>
