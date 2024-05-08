import React, { Component } from 'react';
import Header from '../components/Header';
import { Link } from 'react-router-dom';
import { api } from '../config';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import moment from 'moment';
import { AuthContext } from '../AuthContextProvider';
import Loading from '../components/Loading';

export class Userprofile extends Component {
  static contextType = AuthContext;
  state = {
    firstName: '',
    lastName: '',
    phone: '',
    email: '',
    dob: '',
    is_loading: false,
    update_password: false,
    old_password: '',
    new_password: '',
    confirm_password: '',
  };

  componentDidMount() {
    this.setState({
      firstName: this.context.user.first_name,
      lastName: this.context.user.last_name,
      phone: this.context.user.contact,
      email: this.context.user.email,
      dob: this.context.user.date_of_birth,
    });
  }

  update_student_profile = () => {
    const { firstName, lastName, dob } = this.state;
    if (firstName === '') {
      toast.error('First Name is required');
    } else if (lastName === '') {
      toast.error('Last Name is required');
    } else if (dob === '') {
      toast.error('Date of Birth is required');
    } else {
      const data = {
        first_name: firstName,
        last_name: lastName,
        dob: dob,
      };
      this.setState({
        is_loading: true,
      });
      fetch(api + 'update_student_profile_api', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: this.context.token,
        },
        body: JSON.stringify(data),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            toast.success('Profile Updated Successfully');
          } else {
            toast.error('Something went wrong');
          }
        })
        .catch((err) => {
          toast.error('Something went wrong');
        })
        .finally(() => {
          this.context.get_vendor_profile(this.context.token);
          this.setState({
            is_loading: false,
          });
        });
    }
  };

  change_password = () => {
    if (this.state.old_password === '') {
      toast.error('Old Password is required');
    } else if (this.state.new_password === '') {
      toast.error('New Password is required');
    } else if (this.state.confirm_password === '') {
      toast.error('Confirm Password is required');
    } else if (this.state.new_password !== this.state.confirm_password) {
      toast.error('New Password and Confirm Password does not match');
    } else {
      const data = {
        old_password: this.state.old_password,
        new_password: this.state.new_password,
        confirm_password: this.state.confirm_password,
      };
      this.setState({
        is_loading: true,
      });
      fetch(api + 'change_password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: this.context.token,
        },
        body: JSON.stringify(data),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            toast.success('Password Changed Successfully');
            this.setState({
              update_password: false,
              old_password: '',
              new_password: '',
              confirm_password: '',
            });
          } else {
            toast.error(data.message);
          }
        })
        .catch((err) => {
          toast.error('Something went wrong');
        })
        .finally(() => {
          this.setState({
            is_loading: false,
          });
        });
    }
  };

  render() {
    return (
      <>
        <Header />
        <div className="app-content container-fluid center-layout mt-2">
          <div className="content-wrapper">
            <div className="content-header row">
              <div className="content-header-left col-md-6 col-12 mb-2">
                <div className="row breadcrumbs-top d-block">
                  <div className="breadcrumb-wrapper col-12">
                    <ol className="breadcrumb  bread-heading">
                      <Link to="/">
                        <li className="breadcrumb-item">
                          <a>Dashboard</a>
                        </li>
                      </Link>
                      <li className="breadcrumb-item active">
                        &nbsp; | User Profile
                      </li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
            <div className="content-body">
              {/* Basic form layout section start */}
              <section id="basic-form-layouts">
                <div className="row match-height">
                  <div className="col-md-12">
                    <div className="card">
                      <div className="card-content collapse show">
                        <div className="card-body">
                          <form className="form">
                            <div className="form-body">
                              <h4 className="form-section">
                                <i className="fa-solid fa-user"></i>User Profile
                              </h4>
                              <div className="row">
                                <div className="col-md-6">
                                  <div className="form-group">
                                    <label htmlFor="projectinput1">
                                      First Name
                                    </label>
                                    <input
                                      type="text"
                                      id="projectinput1"
                                      className="form-control"
                                      placeholder="First Name"
                                      name="fname"
                                      value={this.state.firstName}
                                      onChange={(e) => {
                                        this.setState({
                                          firstName: e.target.value,
                                        });
                                      }}
                                    />
                                  </div>
                                </div>
                                <div className="col-md-6">
                                  <div className="form-group">
                                    <label htmlFor="projectinput2">
                                      Last Name
                                    </label>
                                    <input
                                      type="text"
                                      id="projectinput2"
                                      className="form-control"
                                      placeholder="Last Name"
                                      name="lname"
                                      value={this.state.lastName}
                                      onChange={(e) => {
                                        this.setState({
                                          lastName: e.target.value,
                                        });
                                      }}
                                    />
                                  </div>
                                </div>
                              </div>
                              <div className="row">
                                <div className="col-md-6">
                                  <div className="form-group">
                                    <label htmlFor="projectinput3">
                                      E-mail
                                    </label>
                                    <input
                                      type="text"
                                      id="projectinput3"
                                      className="form-control"
                                      placeholder="E-mail"
                                      name="email"
                                      value={this.state.email}
                                      onChange={(e) => {
                                        this.setState({
                                          email: e.target.value,
                                        });
                                      }}
                                      disabled
                                    />
                                  </div>
                                </div>
                                <div className="col-md-6">
                                  <div className="form-group">
                                    <label htmlFor="projectinput4">
                                      Contact Number
                                    </label>
                                    <input
                                      maxLength={10}
                                      type="text"
                                      id="projectinput4"
                                      className="form-control"
                                      placeholder="Phone"
                                      name="phone"
                                      value={this.state.phone}
                                      onChange={(e) => {
                                        this.setState({
                                          phone: e.target.value,
                                        });
                                      }}
                                      disabled
                                    />
                                  </div>
                                </div>
                                <div className="col-md-6">
                                  <div className="form-group">
                                    <label htmlFor="issueinput3">
                                      Enter DOB{' '}
                                    </label>
                                    <input
                                      type="date"
                                      id="issueinput3"
                                      className="form-control"
                                      name="dateopened"
                                      data-toggle="tooltip"
                                      data-trigger="hover"
                                      data-placement="top"
                                      data-title="Date Opened"
                                      value={moment(this.state.dob).format(
                                        'YYYY-MM-DD'
                                      )}
                                      onChange={(e) =>
                                        this.setState({
                                          dob: e.target.value,
                                        })
                                      }
                                    />
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div className="col-md-12 d-flex justify-content-end align-items-center">
                              {!this.state.update_password && (
                                <button
                                  className="btn btn-danger mr-1"
                                  onClick={() => {
                                    this.setState({ update_password: true });
                                  }}
                                >
                                  Update Password
                                </button>
                              )}
                              {this.state.is_loading ? (
                                <button className="btn btn-primary" disabled>
                                  Submitting...
                                </button>
                              ) : (
                                <button
                                  className="btn btn-primary"
                                  onClick={() => {
                                    this.update_student_profile();
                                  }}
                                >
                                  Submit
                                </button>
                              )}
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {this.state.update_password && (
                  <div className="card mb-4">
                    <div className="card-body">
                      <div className="row mt-4">
                        <div className="col-md-4 form-group mb-3">
                          <label htmlFor="website2">Old Password</label>
                          <input
                            type="text"
                            className="form-control"
                            id="website2"
                            placeholder="Enter Old Password"
                            value={this.state.old_password}
                            onChange={(e) => {
                              this.setState({ old_password: e.target.value });
                            }}
                          />
                        </div>
                        <div className="col-md-4 form-group mb-3">
                          <label htmlFor="website2">New Password</label>
                          <input
                            type="text"
                            className="form-control"
                            id="website2"
                            placeholder="Enter New Password"
                            value={this.state.new_password}
                            onChange={(e) => {
                              this.setState({ new_password: e.target.value });
                            }}
                          />
                        </div>
                        <div className="col-md-4 form-group mb-3">
                          <label htmlFor="website2">Confirm New Password</label>
                          <input
                            type="text"
                            className="form-control"
                            id="website2"
                            placeholder="Confirm New Password"
                            value={this.state.confirm_password}
                            onChange={(e) => {
                              this.setState({
                                confirm_password: e.target.value,
                              });
                            }}
                          />
                        </div>
                        <div className="col-md-12 d-flex justify-content-end align-items-center">
                          {this.state.update_password && (
                            <button
                              className="btn btn-danger mr-1"
                              onClick={() => {
                                this.setState({ update_password: false });
                              }}
                            >
                              Cancel
                            </button>
                          )}
                          {this.state.is_loading ? (
                            <button className="btn btn-primary" disabled>
                              Updating...
                            </button>
                          ) : (
                            <button
                              className="btn btn-primary"
                              onClick={() => {
                                this.change_password();
                              }}
                            >
                              Update Password
                            </button>
                          )}
                        </div>
                      </div>
                    </div>
                  </div>
                )}
              </section>
              {/* // Basic form layout section end */}
            </div>
          </div>
        </div>
      </>
    );
  }
}

export default Userprofile;
