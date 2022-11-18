<section id="invitation">
    <p id=invitationEvent> Event Name </p>
    <p id=invitationInviter> Inviter Name </p>
    @if (Auth::check()) <!-- if status == null-->
        <div id=invitationStatus>
            <div id="invitationAccept"> 
                <p> accept </p>
            </div>
            <div id="invitationDecline"> 
                <p> decline </p>
            </div>
        </div> 
    @else
    <p id=invitationStatusAnswer> Accepted </p>
    @endif
</section>
