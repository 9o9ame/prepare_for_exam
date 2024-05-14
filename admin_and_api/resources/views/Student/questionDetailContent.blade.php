@forelse($data as $key => $q)
    <div class="col-xl-12 col-md-6 col-12 non-ppt">
        <div class="card cursor-pointer question-detail" url="{{route('fetch-question-detail',$q->id)}}">
            <div class="card-content">
                <div class="card-body pb-1 cursor-pointer {{ $key % 2 == 0 ? '' : 'bg-success' }}">
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
<div class="row mt-2 ppt d-none">
    <div class="col-md-12">
        <div class="card text-left ">
            <div class="card-body cursor-pointer">
                <div class="row">
                    <div class="col-md-12" style="height: 200px; border-radius: 10px;">
                        <p to="/" class="card-link" style="text-align: center; color: blue;">PPT, Text,
                            Pictures, Videos Etc</p>
                        <textarea id="userinput8" rows="8" class="form-control border-primary" name="bio" placeholder=""></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
