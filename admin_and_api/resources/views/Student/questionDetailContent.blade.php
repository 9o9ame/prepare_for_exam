<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <form action="{{ route('fetch-question') }}" method="post" id="fetch-question">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $request->subject_id ?? '' }}">
                        <input type="hidden" name="exam_id" value="{{ $request->exam_id ?? '' }}">
                        <input type="hidden" name="board_id" value="{{ $request->board_id ?? '' }}">
                        <input type="hidden" name="topic" value="{{ $request->topic ?? '' }}">
                        <input type="hidden" name="subtopic" value="{{ $request->subtopic ?? '' }}">
                        <button type="submit"
                            class="btn mr-1 @if (isset($request->question_type) && $request->question_type == 'easy') bg-success text-white @endif btn-outline-success E  fetch-question-sub"
                            question_type="easy">Easy</button>
                        <button type="submit"
                            class="btn mr-1 @if (isset($request->question_type) && $request->question_type == 'medium') bg-warning text-white @endif btn-outline-warning M fetch-question-sub"
                            question_type="medium">Medium</button>
                        <button type="submit"
                            class="btn mr-1 @if (isset($request->question_type) && $request->question_type == 'hard') bg-danger text-white @endif btn-outline-danger H fetch-question-sub"
                            question_type="hard">Hard</button>
                    </form>
                </div>
                <div class="col-md-6  d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-info mr-1 style" types="question">Exam Style
                        Questions</button>
                    <button type="button" class="btn btn-sm btn-outline-info mr-1 style"
                        types="revision">RevisionNotes</button>
                </div>
            </div>
            <div class="row mt-2">


                @forelse($data as $key => $q)
                    <div class="col-xl-12 col-md-6 col-12 non-ppt">
                        <form method="post" class="question-detail-form">
                            @csrf
                            <input type="hidden" name="subject_id" value="{{ $request->subject_id ?? '' }}">
                            <input type="hidden" name="exam_id" value="{{ $request->exam_id ?? '' }}">
                            <input type="hidden" name="board_id" value="{{ $request->board_id ?? '' }}">
                            <input type="hidden" name="topic" value="{{ $request->topic ?? '' }}">
                            <input type="hidden" name="subtopic" value="{{ $request->subtopic ?? '' }}">
                            <input type="hidden" name="question_type" value="{{ $request->question_type ?? '' }}">
                            <input type="hidden" name="question_id" value="{{ $q->id ?? '' }}">
                            <div class="card cursor-pointer question-detail">
                                <div class="card-content">
                                    <div
                                        class="card-body pb-1 cursor-pointer @if (isset($q->reads) && $q->reads->type == 'complete') bg-success  @elseif(isset($q->reads) && $q->reads->type == 'revisit') bg-danger text-white @endif">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-1"><i class="cc XRP" title="XRP"></i>Question
                                                    {{ $key + 1 }}</h4>
                                                <h6 class="text-black">
                                                    <p>{!! $q->question ?? '' !!}</p>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="row mt-2 non-ppt">
                        <div class="row"
                            style="display: flex; justify-content: center; align-items: center; flex-direction: column; height: 80vh; width: 100%;">
                            <div class="text-center"><img src="{{ asset('assets/images/no_question.webp') }}"
                                    alt="" style="width: 300px;">
                                <h3>No Question Found</h3>
                            </div>
                        </div>
                    </div>
                @endforelse
                <div class="row mt-2 ppt d-none">
                    <div class="col-md-12">
                        <div class="card text-left ">
                            <div class="card-body cursor-pointer">
                                <div class="row">
                                    <div class="col-md-12" style="height: 200px; border-radius: 10px;">
                                        <p to="/" class="card-link" style="text-align: center; color: blue;">
                                            PPT,
                                            Text,
                                            Pictures, Videos Etc</p>
                                        <textarea id="userinput8" rows="8" class="form-control border-primary" name="bio" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
