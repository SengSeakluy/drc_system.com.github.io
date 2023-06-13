
    <div class="card rounded">
        <div class="wrap-header d-flex justify-content-between p-3">
            <h3 class="mr-auto">Template</h3>
            <input class='choise' id="bday-month" type="month" name="bday-month" value="2001-06"/>
        </div>
        <div class="card-body py-3 px-3">
            {!! $usersChart->container() !!}
            {!! $usersChart->script() !!}
        </div>
    </div>