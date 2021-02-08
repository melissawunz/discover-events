<div class="jumbotron">
    <h1>$Event.Title</h1>
</div>
<div class="container pt-4 pb-5">
    <% with $Event %>
    <div class="row">
        <div class="col-sm-8">
            <div class="photo" style="background-image: url({$Photo.ScaleHeight(400).URL}); background-position: center; background-repeat: no-repeat; width: 100%; height: 330px">
            </div>
            <div class="description my-4 px-3">
                $Description
            </div>
            <div class="attend-button">
                <a href="$AttendLink" type="button" class="mx-3 btn btn-success btn-lg">Attend</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="d-flex justify-content-start mb-3">
                <% loop $Hosts %>
                    <div class="host">
                        <div class="rounded">
                            $Profile.ScaleWidth(75)
                        </div>
                        <div>
                            $FirstName $LastName
                        </div>
                    </div>
                <% end_loop %>
            </div>
            <div class="rounded-sm shadow p-3 bg-white">
                <div class="date mb-3">
                    $EventDate.Nice
                </div>
                <div class="time mb-3">
                    $StartTime.Nice to $EndTime.Nice
                </div>
                <div class="location mb-3">
                    $Location.Location
                </div>
                <div id="map" class="google-map">
                </div>
            </div>
        </div>
    </div>
    <% end_with %>
</div>