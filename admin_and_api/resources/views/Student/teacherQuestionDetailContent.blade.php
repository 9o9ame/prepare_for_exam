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
             <div class="col-12 cursor-pointer question-check" question_id="{{ $q->id ?? '' }}" >
                 <label class="checkbox checkbox-primary d-flex align-items-start w-100 " for="vehicle{{ $q->id ?? '' }}">
                     <input type="checkbox" id="vehicle{{ $q->id ?? '' }}" name="vehicle[]" value="{{ $q->id ?? '' }}">
                     <div class="card bg-infos w-100 ms-4">
                         <div class="card-content">
                             <div class="card-body pb-1 cursor-pointer ">
                                 <h4 class="text-black mb-1">Question {{ $key + 1 }}</h4>
                                 <h6 class="text-black">
                                     <p>{!! $q->question ?? '' !!}</p>
                                 </h6>
                                 <i class="fa-solid fa-eye staricon stariconbg question-view-icon" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                             </div>
                         </div>
                     </div>
                 </label>
             </div>
         </form>
     </div>
 @empty
     <div class="row mt-2 non-ppt">
         <div class="row"
             style="display: flex; justify-content: center; align-items: center; flex-direction: column; height: 80vh; width: 100%;">
             <div class="text-center"><img src="{{ asset('assets/images/no_question.webp') }}" alt=""
                     style="width: 300px;">
                 <h3>No Question Found</h3>
             </div>
         </div>
     </div>
 @endforelse
