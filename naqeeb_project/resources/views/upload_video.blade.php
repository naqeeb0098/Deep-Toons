<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui" />
    <title>Upload Videos</title>
    
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-extended.min.css')}}" />
    
    <!-- BEGIN: Dropzone CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dropzone.min.css')}}" />

    <!-- BEGIN: File Uploader CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/form-file-uploader.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/toaster/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-light shadow">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">DEEP TOONS</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="{{url('/')}}">Upload Images</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{url('uploadVideos')}}">Upload Videos</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="container-fluid">
        <!-- button file upload starts -->
            <div class="row mt-2">
                <div class="offset-lg-2 col-lg-8 col-12">
                    <div class="text-center">
                        <h1 class="heading">Upload Video</h1>
                    </div>
                    <div class="card-body text-center">
                        <button id="select-files" class="btn mb-1 uploader_btn">
                            Select Video
                        </button>
                        <form action="{{url('uploadFiles')}}" class="dropzone dropzone-area" id="dpz-btn-select-files">
                            @csrf
                            <input type="hidden" name="type" value="videos">
                            <div class="dz-message">
                                Drop files here or click button to upload.
                            </div>
                        </form>
                        <div class="mt-2">
                            <div class="progress d-none">
                                <div class="progress-bar" style="width:0%">0%</div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn uploader_btn" id="upload">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- button file upload ends -->
    </div>

    <script src="{{asset('assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('assets/js/dropzone.min.js')}}"></script>
    <script src="{{asset('assets/toaster/toastr.min.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#dpz-btn-select-files", { 
            autoProcessQueue: false,
            clickable: '#select-files',
            paramName : 'files',
            parallelUploads: 1, 
            chunking: true,
            chunkSize: 10 * 1024 * 1024,
            parallelChunkUploads: false,
            retryChunks: true,
            retryChunksLimit: 3,
            maxFilesize: 2000,
            maxFiles : 1,
            addRemoveLinks: true,
            dictRemoveFile: ' Remove',
            dictCancelUploadConfirmation: 'Are you sure you want to cancel this upload?',
            acceptedFiles: ".mp4,.mkv,.WebM,.WMV",
            init: function() {
                this.on("error",function(file, message) {
                    this.removeFile(file);
                    $('.progress').addClass('d-none');
                    $('.progress-bar').width("0%");
                    $('.progress-bar').text("0%");
                    toastr.error(message);
                });
                this.on("uploadprogress",function(file, progress, bytesSent) {
                    $('.progress').removeClass('d-none');
                    $('.progress-bar').width(parseInt(progress)+"%");
                    $('.progress-bar').text(parseInt(progress)+"%");
                });
                this.on("success", function(file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        this.removeAllFiles(true);
                        $('.progress').addClass('d-none');
                        $('.progress-bar').width("0%");
                        $('.progress-bar').text("0%");
                        toastr.success("Video Uploaded successfully");
                    }
                });
            }
        });
        $('#upload').click(function(){
            myDropzone.processQueue();
        });
    </script>
</body>
<!-- END: Body-->

</html>