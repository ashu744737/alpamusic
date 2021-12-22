<div class="tab-pane p-3" id="timeline" role="tabpanel">
    @if(isAdmin() || isSM())
        @include('investigation.timeline', ['type'=>'admin','invn' => $invn])
    @elseif(isClient())
        @include('investigation.timeline', ['type'=>'client','invn' => $invn])
     @elseif(isInvestigator() || isDeliveryboy())
        @include('investigation.timeline', ['type'=>'other','invn' => $invn])   
    @endif
</div>