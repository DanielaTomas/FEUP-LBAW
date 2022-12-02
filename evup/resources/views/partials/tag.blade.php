<button id="tag" onclick="filterTag({{$tag -> tagid}})" class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 focus:bg-blue-700 focus:text-white bg-indigo-400 text-blue-700 rounded-full">
    <p data-id="{{$tag -> tagid}}"> {{$tag -> tagname}}</p>
</button>
