import React, { Component } from 'react';
import { useLocation, useParams, useNavigate } from 'react-router-dom';
import logo from '../assets/logos/main_logo.png';
import { Link } from 'react-router-dom';
import { api } from '../config';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
export class Signup extends Component {
  constructor(props) {
    super(props);
    this.state = {
      email: '',
      password: '',
      isloading: false,
      hidden: true,
      countries: [],
      exams: [],
      subjects: [],
      boards: [],
      email: '',
      username: '',
      dob: '',
      password: '',
      repassword: '',
      exam: '',
      subject: '',
      board: '',
      confirmPassword: '',
      school: '',
      userType: 'student',
      isloading: false,
    };
    this.toggleShow = this.toggleShow.bind(this);
  }

  toggleShow() {
    this.setState({ hidden: !this.state.hidden });
  }

  componentDidMount() {
    this.fetchCountries();
    this.fetchExams();
  }

  // responseGoogle = (response) => {
  //   console.log(response);
  // };
  // responseFacebook = (response) => {
  //   console.log(response);
  // };
  // authHandler = (err, data) => {
  //   console.log(err, data);
  // };

  fetchCountries = () => {
    fetch(api + 'fetch-country', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ countries: json.data });
        } else {
          this.setState({ countries: [] });
        }
      })
      .catch((error) => console.error(error))
      .finally(() => {});
  };

  fetchExams = () => {
    fetch(api + 'fetch-exams', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ exams: json.data });
        } else {
          this.setState({ exams: [] });
        }
      })
      .catch((error) => console.error(error))
      .finally(() => {});
  };

  fetchSubjects = (e) => {
    fetch(api + 'fetch-subjects?exam_id=' + e, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ subjects: json.data[0].subjects });
        } else {
          this.setState({ subjects: [] });
        }
      })
      .catch((error) => console.error(error))
      .finally(() => {});
  };

  fetchBoards = (e, f) => {
    fetch(api + 'fetch-boards?exam_id=' + e + '&subject_id=' + f, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ boards: json.data });
        } else {
          this.setState({ boards: [] });
        }
      })
      .catch((error) => console.error(error))
      .finally(() => {});
  };

  signup = () => {
    if (this.state.first_name === '') {
      toast.error('Please enter first name');
      return;
    }

    if (this.state.last_name === '') {
      toast.error('Please enter last name');
      return;
    }
    if (this.state.email === '') {
      toast.error('Please enter email');
      return;
    }

    if (this.state.country === '') {
      toast.error('Please select country');
      return;
    }

    if (this.state.contact === '') {
      toast.error('Please enter contact');
      return;
    }

    if (this.state.dob === '') {
      toast.error('Please enter date of birth');
      return;
    }

    if (this.state.password === '') {
      toast.error('Please enter password');
      return;
    }

    if (this.state.school === '') {
      toast.error('Please enter School name');
      return;
    }

    if (this.state.password.length < 8) {
      toast.error('Password must be at least 8 characters');
      return;
    }

    if (this.state.userType === '') {
      toast.error('Please select user type');
      return;
    }

    if (this.state.password !== this.state.confirmPassword) {
      toast.error('Password and confirm password does not match');
      return;
    }

    this.setState({ isloading: true });

    fetch(api + 'create-student-profile', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        first_name: this.state.firstName,
        last_name: this.state.lastName,
        country: this.state.country,
        email: this.state.email,
        contact: this.state.contact,
        date_of_birth: this.state.dob,
        password: this.state.password,
        confirm_password: this.state.confirmPassword,
        school: this.state.school,
        registration_type: this.state.userType,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          toast.success(
            'Signup successfully, We have sent you an email for activate your account.'
          );

          this.props.navigate('/signin');
        } else {
          toast.error(json.error);
        }

        this.setState({ isloading: false });
      })
      .catch((error) => console.error(error))
      .finally(() => {});
  };

  render() {
    return (
      <>
        <div className="app-content center-layout backgroudimage ">
          <div className="content-overlay" />
          <div className="content-wrapper">
            <div className="content-body ">
              <section className="row navbar-flexbox-container ">
                <div className="col-12 d-flex align-items-center justify-content-center my-4  ">
                  <div className="col-lg-8 col-md-8 col-10  p-0">
                    <div className="card border-grey border-lighten-3 m-0">
                      <div className="card-header  border-0">
                        <div className="card-title text-center">
                          <img
                            src={logo}
                            alt="branding logo"
                            className="sign-logo"
                          />
                        </div>
                      </div>
                      <div className="card-content">
                        <p className="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2">
                          <span>Sign Up to a new account</span>
                        </p>
                        <div className="card-body pt-0">
                          <form
                            className="form-horizontal row"
                            action="https://demos.pixinvent.com/modern-html-admin-template/html/ltr/horizontal-menu-template/index.html"
                            noValidate=""
                          >
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                First Name
                              </label>
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type="text"
                                  className="form-control"
                                  id="user-password"
                                  placeholder="First Name"
                                  required=""
                                  value={this.state.firstName}
                                  onChange={(e) =>
                                    this.setState({ firstName: e.target.value })
                                  }
                                />
                                <div className="form-control-position">
                                  <i className="fa-regular fa-user"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Last Name
                              </label>
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type="text"
                                  className="form-control"
                                  id="user-password"
                                  placeholder="Last Name"
                                  required=""
                                  value={this.state.lastName}
                                  onChange={(e) =>
                                    this.setState({ lastName: e.target.value })
                                  }
                                />
                                <div className="form-control-position">
                                  <i className="fa-regular fa-user"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                School Name
                              </label>
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type="text"
                                  className="form-control"
                                  id="user-password"
                                  placeholder="Enter  Your School Name"
                                  required=""
                                  value={this.state.school}
                                  onChange={(e) =>
                                    this.setState({ school: e.target.value })
                                  }
                                />
                                <div className="form-control-position">
                                  <i className="fa-solid fa-house"></i>
                                </div>
                              </fieldset>
                            </div>

                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Contact No
                              </label>
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type="tel"
                                  maxlength="10"
                                  className="form-control"
                                  id="user-password"
                                  placeholder="Enter Contact No"
                                  required=""
                                  onChange={(e) => {
                                    this.setState({ contact: e.target.value });
                                  }}
                                />
                                <div className="form-control-position">
                                  <i className="fa-solid fa-phone"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Email Address
                              </label>
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type="email"
                                  className="form-control"
                                  id="user-password"
                                  placeholder="Enter Email Address"
                                  required=""
                                  value={this.state.email}
                                  onChange={(e) =>
                                    this.setState({ email: e.target.value })
                                  }
                                />
                                <div className="form-control-position">
                                  <i className="fa-regular fa-envelope"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Date of Birth
                              </label>
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type="date"
                                  className="form-control"
                                  id="user-password"
                                  placeholder="Enter date of birth"
                                  required=""
                                  value={this.state.dob}
                                  onChange={(e) =>
                                    this.setState({
                                      dob: e.target.value,
                                    })
                                  }
                                />
                                <div className="form-control-position">
                                  <i className="fa-regular fa-calendar-days"></i>
                                </div>
                              </fieldset>
                            </div>

                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Country
                              </label>

                              <div className="form-group ">
                                <select
                                  name=""
                                  id=""
                                  className="form-control"
                                  onChange={(e) => {
                                    this.setState({ country: e.target.value });
                                  }}
                                >
                                  <option value="">Select Country</option>
                                  {this.state.countries.map((values, index) => {
                                    return (
                                      <option
                                        id={index}
                                        value={values.phonecode}
                                      >
                                        {values.name}
                                      </option>
                                    );
                                  })}
                                </select>
                              </div>
                            </div>
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Registraion Type
                              </label>

                              <div className="form-group ">
                                <select
                                  name=""
                                  id=""
                                  className="form-control"
                                  onChange={(e) => {
                                    this.setState({ userType: e.target.value });
                                  }}
                                  value={this.state.userType}
                                >
                                  <option disabled selected>
                                    Select Registration Type
                                  </option>
                                  <option value="student">Student</option>
                                  <option value="teacher">Teacher</option>
                                </select>
                              </div>
                            </div>

                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Password
                              </label>
                              <div className="position-relative">
                                <input
                                  type={this.state.hidden ? 'password' : 'text'}
                                  className="form-control "
                                  name="formfield1"
                                  placeholder="password"
                                  onChange={(e) => {
                                    this.setState({ password: e.target.value });
                                  }}
                                />
                                <i
                                  className={
                                    this.state.hidden
                                      ? 'fas fa-eye position-absolute'
                                      : 'fas fa-eye-slash position-absolute'
                                  }
                                  onClick={() => {
                                    this.setState({
                                      hidden: !this.state.hidden,
                                    });
                                  }}
                                  style={{
                                    top: '14px',
                                    right: '6px',
                                  }}
                                ></i>
                              </div>
                            </div>
                            <div className="col-md-6">
                              <label
                                htmlFor="username"
                                className="form-entry-heading"
                              >
                                Confirm Password
                              </label>

                              <div className="position-relative">
                                <input
                                  type={this.state.hidden ? 'password' : 'text'}
                                  className="form-control "
                                  name="formfield1"
                                  placeholder="password"
                                  onChange={(e) => {
                                    this.setState({
                                      confirmPassword: e.target.value,
                                    });
                                  }}
                                />
                                <i
                                  className={
                                    this.state.hidden
                                      ? 'fas fa-eye position-absolute'
                                      : 'fas fa-eye-slash position-absolute'
                                  }
                                  onClick={() => {
                                    this.setState({
                                      hidden: !this.state.hidden,
                                    });
                                  }}
                                  style={{
                                    top: '14px',
                                    right: '6px',
                                  }}
                                ></i>
                              </div>
                            </div>
                            <div className="w-100 d-flex">
                              {this.state.isloading ? (
                                <button
                                  type="button"
                                  className="btn btn-info w-50 my-1 btn-rounded  rounded-pill mx-auto"
                                  disabled
                                  style={{
                                    cursor: 'not-allowed',
                                    opacity: '0.5',
                                  }}
                                >
                                  <span>Signing up...</span>
                                </button>
                              ) : (
                                <button
                                  className="btn btn-info w-50 my-1 btn-rounded  rounded-pill mx-auto"
                                  type="button"
                                  onClick={() => {
                                    this.signup();
                                  }}
                                >
                                  Sign Up
                                </button>
                              )}
                            </div>
                          </form>
                        </div>

                        <p className="text-center">
                          Already a user ? Click here to{' '}
                          <Link to="/signin" className="card-link">
                            Sign In
                          </Link>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </>
    );
  }
}

function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <Signup {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
