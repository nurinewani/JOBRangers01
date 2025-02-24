@foreach ($topRecruiters as $recruiter)
<tr>
    <td>{{ $recruiter->name }}</td>
    <td>{{ $recruiter->email }}</td>
    <td>{{ $recruiter->jobs_count }}</td>
</tr>
@endforeach