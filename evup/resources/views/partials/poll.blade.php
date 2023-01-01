<div id="poll{{$poll->pollid}}" class=' justify-center items-center'>
	<div class='bg-white rounded-md  shadow-lg'>
        <p id="TotalVotes{{$poll->pollid}}" class="invisible text-xs">{{$poll->nranswers()}} </p>
		<p class='text-2xl bg-teal-700 px-4 py-3 text-black font-bold rounded-t-md'>{{$poll->pollcontent}}</p>
        @if($poll->hasAnswered($user->userid))
        <div class='flex flex-col w-full gap-3 pt-3 pb-2 px-2 relative'>
            @foreach($poll->poll_options()->get() as $opt)
			<div class='relative w-full h-8'>
                <div class="flex justify-between mb-1">
                    <span class="text-base font-medium text-blue-700 dark:text-white">
                    @if($opt->voted($user->userid))
                    <input type="radio"  checked disabled> {{$opt->optioncontent}}</span>
                    @else
                    <input  type="radio"  value="0" disabled> {{$opt->optioncontent}}</span>
                    @endif

                    <span class="text-sm font-medium text-blue-700 dark:text-white">{{round($opt->nanswers()/$poll->nranswers()*100,2)}} %</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div id="bar" class="bg-blue-600 h-2.5 rounded-full" style="width: {{round($opt->nanswers()/$poll->nranswers()*100,2)}}%"></div>
                </div>
		    </div>
            @endforeach
        </div>
        @else
        <div class='flex flex-col w-full gap-3 pt-3 pb-2 px-2 relative'>
            @foreach($poll->poll_options()->get() as $opt)
			<div  class='beforeOption relative w-full h-8'>
                <div class="option flex justify-between mb-1">
                    <span class="text-base font-medium text-blue-700 dark:text-white">
                    <input type="radio" onclick="voteOption({{ $opt->polloptionid }},{{$poll->pollid}})" value="0"> {{$opt->optioncontent}}</span>
                    <p class="invisible">{{$opt->nanswers()}} </p>
                </div>
		    </div>
            @endforeach
        </div>
        @endif
        <br>
	</div>
</div>
<br><br>