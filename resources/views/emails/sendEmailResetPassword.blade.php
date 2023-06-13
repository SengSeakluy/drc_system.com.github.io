
<tbody>
    <tr style="background-color: #f6f6f6;">
        <td style="width: 50%;padding:20px;">
            <p style="text-align:center;">Hello {{ $details->name }}</p>
            <p style="text-align:center;">Someone has requested a link to change your password, and you can do this through the link below.</p>
            <p style="text-align:center;"><a href="{{url('admin/change-password')}}/{{\Crypt::encrypt($details->id)}}" target="_blank"  style="    text-decoration: none;
                    padding: 9px 13px;
                    background-color: #24e4d7;
                    color: #222;
                    font-weight: 500;
                    border-radius: 3px;">Reset Password</a></p>
            <p style="text-align:center;">If you didn't request this, please ignore this email.</p>
            <p style="text-align:center;">Your password won't change until you access the link above and create a new one.</p>
        </td>
    </tr>
</tbody>