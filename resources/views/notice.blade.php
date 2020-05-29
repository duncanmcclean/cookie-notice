<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

<div class="fixed bottom-0 right-0">
    <form class="bg-white rounded-lg mr-6 mb-6 p-6 md:w-1/3 float-right" action="{{ route('statamic.cookie-notice.store') }}" method="POST">
        <h2 class="font-semibold text-2xl mb-1">This site uses cookies</h2>
        <p class="text-sm">We use cookies on this site so we can provide you with personalised content, ads and to analyse our website's traffic. By continuing to use this website, you consent to cookies.</p>

        @csrf

        <div class="flex flex-col my-2">
            @foreach($config['groups'] as $groupName => $group)
                <label class="mb-1" for="group_{{ str_slug($groupName) }}">
                    <input id="group_{{ str_slug($groupName) }}" type="checkbox" name="group_{{ str_slug($groupName) }}" @if($group['toggle_by_default'] || $group['required']) checked @endif @if($group['required']) required value="on" onclick="this.checked = true" @endif>
                    {{ $groupName }} @if($group['required']) <span class="text-red-600 text-xs">required</span> @endif
                </label>
            @endforeach
        </div>

        <button class="bg-blue-500 hover:bg-blue-600 rounded text-center text-white px-6 py-2" type="submit">Accept</button>
    </form>
</div>