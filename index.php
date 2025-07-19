<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Amivote – University Elections with Online Voting</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php if (isset($_GET['signup'])): ?>
        <?php if ($_GET['signup'] === 'success'): ?>
            <div class="alert alert-success text-center">Registration successful! Please log in.</div>
        <?php elseif ($_GET['signup'] === 'fail'): ?>
            <div class="alert alert-danger text-center">Registration failed. Email may already be used.</div>
        <?php elseif ($_GET['signup'] === 'empty'): ?>
            <div class="alert alert-warning text-center">Please fill in all fields.</div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid px-2 d-flex align-items-center justify-content-between">

            <!-- Amivote + logo -->
            <a class="navbar-brand fw-bold d-flex align-items-center mb-0" href="#">
                <span class="amivote-logo-text">
                    <span class="amivote-accent">A</span>miVote
                </span>
                <img src="amitylogo.png" alt="Amity University Logo" class="ms-2" style="height: 49px;" />
            </a>

            <!-- Right side -->
            <div class="d-flex align-items-center gap-2">
                <a href="https://www.amity.edu/gurugram/about-us" target="_blank"
                    class="badge bg-primary text-decoration-none" title="Click to know more">
                    Amity University
                </a>
                <!-- Navbar (add inside .container, before Amity badge) -->
                <div class="d-flex align-items-center me-3">
                    <button class="btn btn-outline-maroon me-2" data-bs-toggle="modal"
                        data-bs-target="#loginModal">Login</button>
                    <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</button>
                </div>

            </div>

        </div>
    </nav>



    </nav>

    <!-- Hero Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left: Text -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-3">University elections with online voting</h1>
                    <p class="lead mb-4">
                        The voting platform of choice for more than 120 universities worldwide. Amivote is designed for
                        secure, efficient, and transparent university, faculty, and student elections at every stage of
                        the process.
                    </p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-maroon" data-bs-toggle="modal"
                            data-bs-target="#contactModal">Contact
                            us</button>
                    </div>
                </div>
                <!-- Right: Image -->
                <div class="col-lg-6 text-center">
                    <img src="https://eligovoting.com/wp-content/uploads/2024/05/University-elections-with-digital-voting.jpg"
                        alt="University elections with digital voting" class="img-fluid rounded shadow"
                        style="max-height: 350px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Online Voting -->
    <section class="py-5 bg-maroon text-white">
        <div class="container">
            <h2 class="mb-4 fw-bold">Why Universities choose online voting.</h2>
            <p class="mb-5">
                Universities are opting for digital voting to simplify the decision-making process for students and
                academic staff. With Amivote eVoting, universities can ensure efficient, transparent and accessible
                elections, facilitating participation and improving the experience of voters and organizational staff.
            </p>
            <!-- Sliding Cards -->
            <div id="benefitsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-phone-fill fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Adapting to new digital habits</h5>
                                        <p class="card-text">Online voting aligns with the digital habits of
                                            contemporary society. Incorporating technology into the democratic process
                                            helps universities align with the preferences and needs of staff and
                                            students.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-universal-access-circle fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Accessibility</h5>
                                        <p class="card-text">Digital voting ensures that all eligible voters, regardless
                                            of location or ability, can participate easily and securely in university
                                            elections.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-speedometer2 fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Efficiency</h5>
                                        <p class="card-text">Digital voting streamlines and speeds up the entire
                                            election process, greatly decreasing the time it takes to set-up, count, and
                                            publish results—from weeks to hours.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-lightbulb-fill fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Innovation</h5>
                                        <p class="card-text">The adoption of digital voting is a step toward
                                            technological innovation and efficient internal decision-making in academia.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-palette2 fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Customization</h5>
                                        <p class="card-text">Amivote offers customizable voting solutions tailored to
                                            the unique needs of each university, supporting a variety of election types
                                            and organizational structures.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-shield-lock-fill fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Security</h5>
                                        <p class="card-text">Advanced encryption and authentication protocols ensure the
                                            integrity and confidentiality of every vote cast in the system.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-speedometer2 fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Efficiency</h5>
                                        <p class="card-text">Digital voting streamlines and speeds up the entire
                                            election process, greatly decreasing the time it takes to set-up, count, and
                                            publish results—from weeks to hours.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-phone-fill fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Adapting to new digital habits</h5>
                                        <p class="card-text">Online voting aligns with the digital habits of
                                            contemporary society. Incorporating technology into the democratic process
                                            helps universities align with the preferences and needs of staff and
                                            students.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block">
                                <div class="card h-100 benefits-card text-maroon">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-lightbulb-fill fs-1"></i>
                                        </div>
                                        <h5 class="card-title fw-bold">Innovation</h5>
                                        <p class="card-text">The adoption of digital voting is a step toward
                                            technological innovation and efficient internal decision-making in academia.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#benefitsCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#benefitsCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- What we can do for you -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="mb-4 fw-bold">What we can do for you</h2>
            <div class="accordion" id="servicesAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingRector">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseRector" aria-expanded="true" aria-controls="collapseRector">
                            Election of the Rector
                        </button>
                    </h2>
                    <div id="collapseRector" class="accordion-collapse collapse show" aria-labelledby="headingRector"
                        data-bs-parent="#servicesAccordion">
                        <div class="accordion-body">
                            Secure and transparent digital voting for the election of university rectors, ensuring broad
                            participation and reliable results.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingStudents">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                            Students' Elections
                        </button>
                    </h2>
                    <div id="collapseStudents" class="accordion-collapse collapse" aria-labelledby="headingStudents"
                        data-bs-parent="#servicesAccordion">
                        <div class="accordion-body">
                            Efficient management of student council and representative elections, with easy access for
                            all students.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFaculty">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFaculty" aria-expanded="false" aria-controls="collapseFaculty">
                            Representatives of faculty and departments
                        </button>
                    </h2>
                    <div id="collapseFaculty" class="accordion-collapse collapse" aria-labelledby="headingFaculty"
                        data-bs-parent="#servicesAccordion">
                        <div class="accordion-body">
                            Digital voting for faculty and departmental representatives, supporting fair and inclusive
                            academic governance.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSenate">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseSenate" aria-expanded="false" aria-controls="collapseSenate">
                            Voting for the Academic Senate
                        </button>
                    </h2>
                    <div id="collapseSenate" class="accordion-collapse collapse" aria-labelledby="headingSenate"
                        data-bs-parent="#servicesAccordion">
                        <div class="accordion-body">
                            Streamlined and secure voting for academic senate positions, ensuring transparency and trust
                            in the process.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTrustees">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTrustees" aria-expanded="false" aria-controls="collapseTrustees">
                            Board of Trustees elections
                        </button>
                    </h2>
                    <div id="collapseTrustees" class="accordion-collapse collapse" aria-labelledby="headingTrustees"
                        data-bs-parent="#servicesAccordion">
                        <div class="accordion-body">
                            Digital solutions for electing board of trustees members, supporting institutional integrity
                            and efficiency.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingRegents">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseRegents" aria-expanded="false" aria-controls="collapseRegents">
                            Board of Regents elections
                        </button>
                    </h2>
                    <div id="collapseRegents" class="accordion-collapse collapse" aria-labelledby="headingRegents"
                        data-bs-parent="#servicesAccordion">
                        <div class="accordion-body">
                            Secure and accessible voting for board of regents, ensuring broad participation and
                            compliance with university policies.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 fw-bold">Success stories</h2>
            <p>Discover how leading universities are transforming governance with Amivote's secure and modern digital
                voting solutions.</p>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">European Student Assembly for UNITA</h5>
                    <p class="card-text">Over 13,000 students voted across 5 European universities.</p>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#successStoryModal">Read the story</a>

                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-maroon text-white text-center">
        <div class="container">
            <h2 class="mb-4 fw-bold">4000+ organizations rely on Amivote for online voting and assemblies. Join them.
            </h2>
            <button class="btn btn-light btn-lg me-2">Free trial</button>
            <button class="btn btn-outline-light btn-lg">Book a demo</button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold">Product</h5>
                    <ul class="list-unstyled">
                        <li>Online Elections</li>
                        <li>Digital Assemblies</li>
                        <li>Voting Software</li>
                        <li>Secure Voting</li>
                        <li>Academic Voting</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold">Use cases</h5>
                    <ul class="list-unstyled">
                        <li>Universities and Academies</li>
                        <li>Student Elections</li>
                        <li>Faculty Voting</li>
                        <li>Board Elections</li>
                        <li>Academic Senate and Institutes</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold">Company</h5>
                    <ul class="list-unstyled">
                        <li>About</li>
                        <li>Contact</li>
                        <li>Careers</li>
                        <li>Privacy Policy</li>
                        <li>Cookie Policy</li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-3">
                <small>© 2025 Amivote. All rights reserved.</small>
            </div>
        </div>
    </footer>
    <!-- Login Modal -->
    <?php
    // Show success message after registration
    $signup_success = isset($_GET['signup']) && $_GET['signup'] === 'success';
    $login_fail = isset($_GET['login']) && $_GET['login'] === 'fail';
    ?>
    <!-- Alerts -->
    <?php if ($signup_success): ?>
        <div class="alert alert-success text-center">Registration successful! Please log in.</div>
    <?php endif; ?>
    <?php if ($login_fail): ?>
        <div class="alert alert-danger text-center">Login failed. Please check your credentials.</div>
    <?php endif; ?>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <ul class="nav nav-pills mb-3 justify-content-center" id="loginTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="voter-login-tab" data-bs-toggle="pill"
                                data-bs-target="#voter-login" type="button" role="tab" aria-controls="voter-login"
                                aria-selected="true">Voter</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-login-tab" data-bs-toggle="pill"
                                data-bs-target="#admin-login" type="button" role="tab" aria-controls="admin-login"
                                aria-selected="false">Admin</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="loginTabContent">
                        <!-- Voter Login -->
                        <div class="tab-pane fade show active" id="voter-login" role="tabpanel"
                            aria-labelledby="voter-login-tab">
                            <form method="post" action="login.php">
                                <input type="hidden" name="role" value="voter">
                                <div class="mb-3">
                                    <label for="voterUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="voterUsername" name="username"
                                        placeholder="Enter your username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="voterPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="voterPassword" name="password"
                                        placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-maroon w-100">Login</button>
                            </form>
                            <div class="text-center mt-3">
                                <span>Don't have an account? <a href="#" id="showSignupFromLogin"
                                        class="text-maroon fw-bold">Sign Up</a></span>
                            </div>
                        </div>
                        <!-- Admin Login -->
                        <div class="tab-pane fade" id="admin-login" role="tabpanel" aria-labelledby="admin-login-tab">
                            <form method="post" action="login.php">
                                <input type="hidden" name="role" value="admin">
                                <div class="mb-3">
                                    <label for="adminUsername" class="form-label">Admin Username</label>
                                    <input type="text" class="form-control" id="adminUsername" name="username"
                                        placeholder="Admin username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="adminPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="adminPassword" name="password"
                                        placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-maroon w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="signupModalLabel">Voter Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" action="register.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name"
                                placeholder="Middle Name">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department"
                                placeholder="Department" required>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-maroon w-100">Sign Up</button>
                    </form>
                    <div class="text-center mt-3">
                        <span>Already have an account? <a href="#" id="showLoginFromSignup"
                                class="text-maroon fw-bold">Login</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Switching Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Show signup modal from login modal
            document.getElementById('showSignupFromLogin').onclick = function (e) {
                e.preventDefault();
                var loginModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal'));
                loginModal.hide();
                var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
                signupModal.show();
            };
            // Show login modal from signup modal
            document.getElementById('showLoginFromSignup').onclick = function (e) {
                e.preventDefault();
                var signupModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('signupModal'));
                signupModal.hide();
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            };

            // Auto-show modals on registration or login error
            <?php if ($signup_success): ?>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            <?php elseif ($login_fail): ?>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            <?php endif; ?>
        });
    </script>





    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-maroon" id="contactModalLabel">Contact Amity University</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <span class="ms-2">+91-99999 88888</span>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <span class="ms-2">info.amitygurugram@gmail.com</span>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <span class="ms-2">Amity University Haryana, Manesar, Gurugram, Haryana, India</span>
                    </div>
                    <div class="mb-3">
                        <strong>Important Details:</strong>
                        <ul class="mb-0">
                            <li>For admissions, call or email above.</li>
                            <li>Office hours: Mon–Fri, 9:00 AM–5:00 PM</li>
                            <li>Website: <a href="https://www.amity.edu/gurugram/about-us" target="_blank"
                                    class="text-maroon">amity.edu/gurugram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Story Modal -->
    <div class="modal fade" id="successStoryModal" tabindex="-1" aria-labelledby="successStoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-maroon" id="successStoryModalLabel">Success stories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <h5 class="fw-bold text-maroon mb-2">European Student Assembly for UNITA</h5>
                    <p class="mb-1">Over 15,000 verified votes across 12 European universities</p>
                    <div class="mb-2">
                        <strong>European Student Assembly</strong><br>
                        15,000 verified votes<br>
                        End-to-end verifiability
                    </div>
                    <h6 class="fw-bold text-maroon mt-4 mb-2">The UNITA Voting Challenge</h6>
                    <p>
                        Organizing a truly representative, secure, and accessible European Student Assembly is as
                        ambitious as it is essential. The UNITA – Universitas Montium Alliance, which includes twelve
                        universities across seven countries – such as the University of Turin, the University of
                        Brescia, and the West University of Timișoara– set out to ensure voting rights for thousands of
                        students spread throughout Europe. This initiative aimed to strengthen European identity and
                        active citizenship by giving students a direct role in shaping the alliance’s strategic
                        decisions.
                    </p>
                    <p>
                        Coordinating this cross-border electoral process meant balancing various demands:
                        multilingualism in voting, verifiability, data protection, and freedom of expression. It also
                        required a digital infrastructure capable of overcoming geographical and technical barriers –
                        delivering an inclusive, GDPR-compliant voting experience. The main priority was clear: to build
                        trust in a system that would turn the European Student Assembly into a real-world example of
                        digital democracy – one that could be replicated in other academic and institutional settings.
                    </p>
                    <h6 class="fw-bold text-maroon mt-4 mb-2">The Solution</h6>
                    <p>
                        Amivote, in collaboration with SEEV Technologies Ltd., provided the ideal solution for the
                        European Student Assembly. By integrating Amivote’s platform with SEEV’s self-enforcing
                        cryptographic technology, it was possible to guarantee verifiable, anonymous, and low-coercion
                        digital elections.
                    </p>
                    <p>
                        SEEV uses an innovative approach that allows each voter to verify the integrity of their vote
                        without relying on third parties. Through self-enforcing cryptographic proofs and publicly
                        accessible bulletin boards, every step is traceable and transparent. End-to-end verifiability
                        (E2E) is the core of the system: it enables each voter to confirm their vote was cast as
                        intended, recorded as cast, and counted as recorded. All this is done without compromising
                        privacy or requiring trust in external actors—thanks to advanced cryptographic protocols.
                    </p>
                    <p>
                        Students from all twelve participating universities – from Universidad Pública de Navarra to
                        Instituto Politécnico da Guarda – were able to vote using their university credentials, from any
                        device.
                    </p>
                    <div class="mb-2">
                        <strong>Key benefits of the solution included:</strong>
                        <ul>
                            <li>Secure and anonymous digital elections</li>
                            <li>Remote access via university credentials</li>
                            <li>End-to-end vote verifiability</li>
                            <li>Coercion resistance enabled by cryptography</li>
                            <li>Multilingual and user-friendly interface</li>
                        </ul>
                    </div>
                    <p>
                        A transparent and accessible process that highlighted students’ European identity—without
                        sacrificing security or ease of use. A result that confirms Amivote’s experience in digital
                        student elections, built over years of collaboration with universities across Europe.
                    </p>
                    <h6 class="fw-bold text-maroon mt-4 mb-2">Results</h6>
                    <p>
                        The European Student Assembly election organized by UNITA saw over 15,000 verified votes,
                        involving twelve academic institutions in Italy, Romania, Spain, Portugal, and beyond. Each
                        university maintained autonomy over candidates while using a harmonized ballot. This synergy
                        strengthened a common identity while respecting cultural and legal diversity. The separation of
                        Amivote and SEEV servers ensured voter anonymity, and results were available in real time – with
                        full transparency.
                    </p>
                    <p>
                        The experience represented a scalable model for secure digital voting – demonstrating how
                        technology can foster inclusion and strengthen institutional trust. UNITA and Amivote have taken
                        a concrete step toward a more open, transparent, and engaging model of university governance. A
                        victory for participation, trust, and innovation, delivering a clear message: digital democracy
                        is already a reality.
                    </p>
                    <div class="mt-4">
                        <em>Want to discover how Amivote can make your university elections secure, transparent, and
                            verifiable? <br>
                            <strong>Start your free trial now and see how to manage an online election in just a few
                                clicks.</strong>
                        </em>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>

</html>