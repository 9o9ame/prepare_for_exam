
<div class="col-md-12 col-lg-12">
    <label>Subject</label>
    <div class="form-group">
        @foreach ($subject as $subject)
            <label><input type="checkbox" name="subject[]"
                    value="{{ $subject->id }}" @if (isset($subject_ids) && in_array($subject->id, $subject_ids)) checked @endif>
                {{ $subject->subject_name }}</label><br>
        @endforeach
    </div>
    <span class="text-danger">
        @error('subject')
            {{ $message }}
        @enderror
    </span>
</div>
