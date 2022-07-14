@extends('layouts.sidebar')

@section('content')

        <h2 class="mb-4"><span class="bi bi-plus"> Add Link</span></h2>

        <form action="{{ route('addLink') }}" method="post">
        @csrf
        <div class="form-group col-lg-8">
          <label>Link*</label>
          <input type="text" name="link" class="form-control" placeholder="https://google.com" required>
        </div>
        <div class="form-group col-lg-8">
          <label>Title</label>
          <input type="text" name="title" class="form-control" placeholder="Internal name (optional)">
        </div>
        <div class="form-group col-lg-8">
          <label for="exampleFormControlSelect1">Button*</label>
          <select class="form-control" name="button">
            @foreach($buttons as $button)
            <option> {{ $button->name }} </option>
            @endforeach
          </select>
          <br>
          <label>* Required fields</label><br>
        </div>
        <div class="row"><button type="submit" class="mt-3 ml-3 btn btn-info">Submit</button><a style="color:white;background-color:#f8b739;" class="mt-3 ml-3 btn" href="{{ url('/studio/links') }}">See all links</a></div>
        </form>

        <br><br><details>
    <summary>More information</summary>
<pre style="color: grey;">
The 'Custom' button allows you to add a custom link, where the text on the button is determined with the link title set above.
The 'Custom_website' button functions similar to the Custom button, with the addition of a function that requests the favicon from the chosen URL and uses it as the button icon.

The 'Space' button will be replaced with an empty space, so buttons could be visually separated into groups. Entering a number between 1-10 in the title section will change the empty space's distance.
The 'Heading' button will be replaced with a sub-heading, where the title defines the text on that heading.
</pre>
</details>

@endsection
