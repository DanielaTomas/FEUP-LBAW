<section class="flex flex-row bg-white border-4 border-gray-200 justify-between p-5 align-center">
    <div class="flex flex-row gap-3">
        <td class="px-4 py-2">
            <p class="font-bold">{{ $invite->event()->first()->eventname }}</p>
        </td>
        <td class="px-4 py-2">
            <p>{{ $invite->sender()->first()->username }}</p>
        </td>
    </div>
    <?php if ($invite['invitationstatus'] == TRUE) { ?>
    <td class="px-4 py-2 ">
        <button
            class="inline-flex text-green-900 items-center px-4 py-2 text-sm font-medium text-center bg-green-100 rounded-full focus:ring-4 focus:outline-none focus:ring-blue-300">
            Aceite</button>
    </td>
    <?php } else if ($invite['invitationstatus'] === FALSE) { ?>
    <td class="px-4 py-2">
        <button
            class="inline-flex text-red-900 items-center  px-4 py-2 text-sm font-medium text-center bg-red-100 rounded-full focus:ring-4 focus:outline-none focus:ring-blue-300">
            Rejeitado</button>
    </td>
    <?php } else{ ?>
    <div class="flex flex-row">
        <div id="here{{ $invite->invitationid }}" class="px-4 py-2">
            <button value="Submit" type="button" id="accept{{ $invite->invitationid }}"
                onclick="acceptInvite({{ $invite->invitationid }})"
                class="inline-flex text-green-900 items-center  px-4 py-2 text-sm font-medium text-center bg-green-100 rounded-full focus:ring-4 focus:outline-none focus:ring-blue-300">
                Aceitar
            </button>
        </div>
        <div class="px-4 py-2">
            <button value="Submit" type="button" id="decline{{ $invite->invitationid }}"
                onclick="declineInvite({{ $invite->invitationid }})"
                class="inline-flex text-red-900 items-center  px-4 py-2 text-sm font-medium text-center bg-red-100 rounded-full focus:ring-4 focus:outline-none focus:ring-blue-300">
                Rejeitar
            </button>
        </div>
    </div>

    <?php } ?>
</section>
