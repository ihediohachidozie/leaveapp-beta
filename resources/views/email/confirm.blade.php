<div class="container">
    <p>Dear {{ $userdata1['firstname'] }},</p>
    <p>Kindly be informed that your leave application request is 
        @if($leave['status'] == 3)
            {{'approved'}}
        @elseif($leave['status'] == 0)
            {{'open'}}
        @else
            {{'rejected'}}
        @endif

    .</p>
    <p>
        @if ($comment)
            {{
                'Reason: '. $comment
            }}
            
        @endif
    </p>
<p><a href="https://leaveapp.ecmterminals.com">Goto App</a></p>
    <p>Regards...</p>  
    
</div