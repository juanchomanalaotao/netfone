<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Book</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid pt-2">
        <div class="row">
            <div class="col-3">
                <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 100%">
                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <svg class="bi me-2" width="40" height="32">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                        <span class="fs-4">Contacts</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <div class="container-fluid no-gutters mb-3">
                                <div class="row no-gutters">
                                    <div class="col-6">
                                        <input type="text" class="form-control cname" placeholder="Contact Name" />
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control cnum" placeholder="Contact Name" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="cols">
                                        <button class="btn btn-success save-contacts">Save</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                All Contacts
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link link-dark">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#speedometer2"></use>
                                </svg>
                                Favorites
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link link-dark">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#table"></use>
                                </svg>
                                Newly Added
                            </a>
                        </li>
                    </ul>
                    <hr>
                </div>
            </div>
            <div class="col">
                <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white" style="width: 100%;">
                    <a href="/" class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none">
                        <svg class="bi me-2" width="30" height="24">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                        <span class="fs-5 fw-semibold">List Contacts</span>
                    </a>


                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <th>Name</th>
                            <th>Contact NO.</th>
                            <th>Action</th>
                        </thead>
                        <tbody class="tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="mutations/mutations.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

<script>
    $(document).ready(function() {
        $('.save-contacts').click(function() {
            let cname = $('.cname').val();
            let cnum = $('.cnum').val();

            $.ajax({
                url: window.location.origin + '/graphql',
                data: {
                    query: create(cname, cnum)
                },
                success: function(res) {
                    console.log(res)
                }
            })
        })

        function getAll() {
            $.ajax({
                url: window.location.origin + '/graphql',
                data: {
                    query: getAllList()
                },
                beforeSend: () => {
                    $('.is-loading').show()
                    $('.tbody').html();
                },
                success: function(res) {
                    let data = res.data.listContacts
                    $(data).each((index, item) => {
                        $('.tbody').append(`
                          <tr>
                            <td>${item.name}</td>
                            <td>${item.contact_no}</td>
                            <td><button class="btn btn-primary editcn" data-id="${item.id}">EDIT</button>
                            <button class="btn btn-danger deletecn" data-id="${item.id}">DELETE</button>
                            </td>
                         </tr>
                        `)
                    })

           
                    $('.editcn').click(function() {
                        let child = $(this).parent().parent().children();
                        let dataID = $(this).attr('data-id');
                        $(child[0]).html(`<input type="text" class="form-control cname-${dataID}" value="${$(child[0]).text()}"/>`)
                        $(child[1]).html(`<input type="text" class="form-control cnum-${dataID}" value="${$(child[1]).text()}"/>`)
                        $(child[2]).html(`<button class="btn btn-success save-edit" data-id="${dataID}">SAVE</button>`)
                        $('.deletecn').click(function(){
                                        $.ajax({
                                        url: window.location.origin + '/graphql',
                                        data: {
                                            query: destroy($(this).attr('data-id'))
                                        },
                                        success: function(res) {
                                            console.log(res)
                                            getAll();
                                        }
                                     })
                                  })
                        $('.save-edit').click(function() {
                            let cname = $(`.cname-${dataID}`).val();
                            let cnum = $(`.cnum-${dataID}`).val();
                            $.ajax({
                                url: window.location.origin + '/graphql',
                                data: {
                                    query: update(dataID, cname, cnum)
                                },
                                success: function(res) {
                                    $(child[0]).html(`<td>${cname}</td>`)
                                    $(child[1]).html(`<td>${cnum}</td>`)
                                     $(child[2]).html(`<td><button class="btn btn-primary editcn" data-id="${dataID}">EDIT</button>
                                      <button class="btn btn-danger deletecn" data-id="${dataID}">DELETE</button></td>`)
                              
                                }
                            })
                        })
                    })
                    $('.is-loading').addClass('d-none')
                    new DataTable('#example');
                }
            })
        }

        getAll()

    })
</script>

</html>