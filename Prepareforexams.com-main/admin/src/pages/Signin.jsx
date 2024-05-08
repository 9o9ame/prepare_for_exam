import React, { Component } from "react";
import logo from "../assets/logos/main_logo.png";
import { Link, useLocation, useNavigate, useParams } from "react-router-dom";
import { api } from "../config";
import { toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { AuthContext } from "../AuthContextProvider";

export class Signin extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      email: "",
      password: "",
      isloading: false,
      hidden: true,
      contact: "",
    };
    this.toggleShow = this.toggleShow.bind(this);
  }

  toggleShow() {
    this.setState({ hidden: !this.state.hidden });
  }

  login = () => {
    if (this.state.contact === "") {
      toast.error("Please enter contact no or email");
      return;
    }

    if (this.state.password === "") {
      toast.error("Please enter password");
      return;
    }

    this.setState({ isloading: true });
    fetch(api + "login-student", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        contact: this.state.contact,
        password: this.state.password,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.msg === "ok") {
          toast.success("Login Successfully");
          const data = {
            token: json.token,
            user_id: json.usr,
            use_type: "done",
          };
          localStorage.setItem("@auth_login", JSON.stringify(data));

          this.context.login("done", json.user, json.token);

          //set the data to access from authguard'
          this.props.navigate("/");
        } else {
          this.setState({ password: "" });
          toast.error(json.errors);
          // toast.error(json.errors[0]);
        }

        this.setState({ isloading: false });
      })
      .catch((error) => console.error(error))
      .finally(() => {});
  };
  render() {
    return (
      <>
        <div className="app-content center-layout  siginpage backgroudimages   ">
          <div className="content-wrapper h-100">
            <div className="content-body h-100">
              <section className="row navbar-flexbox-container  h-100">
                <div className="col-12 d-flex align-items-center justify-content-center m-0">
                  <div className="col-lg-4 col-md-8 col-10  p-0">
                    <div className="card border-grey border-lighten-3 m-0">
                      <div className="card-header  border-0">
                        <div className="card-title text-center">
                          <img
                            src={logo}
                            alt="branding logo"
                            className="siginlogo"
                          />
                        </div>
                      </div>
                      <div className="card-content">
                        <p className="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2">
                          <span>Sign In to your account</span>
                        </p>
                        <div className="card-body pt-0">
                          <form
                            className="form-horizontal"
                            action="https://demos.pixinvent.com/modern-html-admin-template/html/ltr/horizontal-menu-template/index.html"
                            noValidate=""
                          >
                            <label
                              htmlFor="username"
                              className="form-entry-heading"
                            >
                              Contact No or Email
                            </label>
                            <fieldset className="form-group position-relative has-icon-left">
                              <input
                                type="text"
                                className="form-control"
                                id="user-name"
                                placeholder="Contact No or Email"
                                required=""
                                onChange={(e) => {
                                  this.setState({ contact: e.target.value });
                                }}
                                onKeyPress={(e) => {
                                  if (e.key === "Enter") {
                                    this.login();
                                  }
                                }}
                                value={this.state.contact}
                              />
                              <div className="form-control-position">
                                <i className="fa-solid fa-phone"></i>
                              </div>
                            </fieldset>
                            <label
                              htmlFor="username"
                              className="form-entry-heading"
                            >
                              Enter Password
                            </label>
                            <div className="position-relative">
                              <fieldset className="form-group position-relative has-icon-left">
                                <input
                                  type={this.state.hidden ? "password" : "text"}
                                  className="form-control "
                                  name="formfield1"
                                  placeholder="password"
                                  onChange={(e) => {
                                    this.setState({ password: e.target.value });
                                  }}
                                  value={this.state.password}
                                  onKeyPress={(e) => {
                                    if (e.key === "Enter") {
                                      this.login();
                                    }
                                  }}
                                />
                                <div className="form-control-position">
                                  <i className="fa-solid fa-lock"></i>
                                </div>
                              </fieldset>
                              <i
                                className={
                                  this.state.hidden
                                    ? "fas fa-eye position-absolute"
                                    : "fas fa-eye-slash position-absolute"
                                }
                                onClick={() => {
                                  this.setState({
                                    hidden: !this.state.hidden,
                                  });
                                }}
                                style={{
                                  top: "14px",
                                  right: "6px",
                                }}
                              ></i>
                            </div>{" "}
                            <div className="w-100 d-flex">
                              {this.state.isloading ? (
                                <button
                                  type="button"
                                  className="btn btn-info w-50 my-1 btn-rounded  rounded-pill mx-auto"
                                  disabled
                                  style={{
                                    cursor: "not-allowed",
                                    opacity: "0.5",
                                  }}
                                >
                                  <span>Signing In...</span>
                                </button>
                              ) : (
                                <button
                                  className="btn btn-info w-50 my-1 btn-rounded  rounded-pill mx-auto"
                                  type="button"
                                  onClick={() => {
                                    this.login();
                                  }}
                                >
                                  Sign In
                                </button>
                              )}
                            </div>
                          </form>
                          <p className="text-center">
                            A New Student ? Click here to &nbsp;
                            <Link to="/signup">Sign Up</Link>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>

        <div className="sidenav-overlay" />
        <div className="drag-target" />
      </>
    );
  }
}
function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <Signin {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
