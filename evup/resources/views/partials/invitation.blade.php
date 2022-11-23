<div class="bg-white p-8 rounded-md w-full">
	<div class=" flex items-center justify-between pb-6">
			<div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
				<div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
					<table class="min-w-full leading-normal">
						<tbody>
							<tr>
								<td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
									<div class="flex items-center">
											<div class="ml-3">
                                                @each('partials.eventName', $invite->event()->get(), 'event')
											</div>
										</div>
								</td>
								<td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
								@each('partials.userName', $invite->sender()->get(), 'user')	
        
								</td>
                                @if (Auth::check()) <!-- if status == null-->
                                    <div id=invitationStatus>
                                        <div id="invitationAccept"> 
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <span
                                                    class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                    <span aria-hidden
                                                        class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                <span class="relative">Aceitar</span>
                                                </span>
                                            </td>
                                        </div>
                                        <div id="invitationDecline"> 
                                           <td class="px-5 py-5 bg-white text-sm">
                                                <span
                                                    class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                                    <span aria-hidden
                                                        class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                                <span class="relative">Rejeitar</span>
                                                </span>
                                            </td>
                                        </div>
                                    </div> 
                                @else
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                <span class="relative">Aceite</span>
                                        </span>
                                </td>
                                @endif
						</tbody>
					</table>
				</div>
			</div>
	</div>

