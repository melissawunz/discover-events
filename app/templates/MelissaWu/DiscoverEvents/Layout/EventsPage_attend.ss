<div class="jumbotron">
    <h1>Discover Events</h1>
</div>
<div class="container pt-4 pb-5">
    <div class="row">
        <div class="col-6 event">
            <div class="card mx-3">
                <% with $Event %>
                <img class="card-img-top" src="$Photo.URL" alt="profile">
                <div class="card-body">
                    <div class="title mb-3">
                        <h3>$Title</h3>
                    </div>
                    <div class="date time">
                        <p>$EventDate.Nice</p>
                        <p>$StartTime.Nice to $EndTime.Nice</p>
                    </div>
                    <div class="location">
                        $Location.Location
                    </div>
                </div>
                <% end_with %>
            </div>
        </div>
        <div class="col-6">
            $Form
        </div>
    </div>
</div>