import React, { Component } from "react";
import logo from "../assets/images/logo.png";
import {
  Link,
  NavLink,
  useLocation,
  useNavigate,
  useParams,
} from "react-router-dom";
import { api } from "../config";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { AuthContext } from "../AuthContextProvider";
import user from "../assets/images/usericon.png";

export class Header extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      showSearchBox: false,
      main_examList: [],
      subjectBoardData: [],
      boardData: [],
      isloading: false,
      exam_id: "",
      subject_id: "",
    };
  }

  componentDidMount() {
    this.fetchData();
  }

  logout = () => {
    this.context.logout();
    toast.success("Logout Successfully");
  };

  handleMenuClick = () => {
    let { setLayoutSettings, settings } = this.props;
    setLayoutSettings(
      merge({}, settings, {
        layout1Settings: {
          leftSidebar: {
            open: settings.layout1Settings.leftSidebar.secondaryNavOpen
              ? true
              : !settings.layout1Settings.leftSidebar.open,
            secondaryNavOpen: false,
          },
        },
      })
    );
  };

  toggleFullScreen = () => {
    if (document.fullscreenEnabled) {
      if (!document.fullscreen) document.documentElement.requestFullscreen();
      else document.exitFullscreen();
    }
  };

  handleSearchBoxOpen = () => {
    let { setLayoutSettings, settings } = this.props;
    setLayoutSettings(
      merge({}, settings, {
        layout1Settings: {
          searchBox: {
            open: true,
          },
        },
      })
    );
  };

  fetchData = () => {
    fetch(api + "fetch_user_exam_data", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: this.context.token,
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ main_examList: json.data });
        } else {
          // toast.error(json.message);
          this.setState({ main_examList: [] });
        }
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        this.setState({ isloading: false });
      });
  };
  render() {
    return (
      <>
        {/* <nav className="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow  navbar-light navbar-brand-center">
          <div className="navbar-wrapper">
            <div className="navbar-container content">
              <div
                className="collapse navbar-collapse d-flex align-items-center justify-content-start"
                id="navbar-mobile"
              >
                <img
                  src={logo}
                  className="main-logo cursor-pointer"
                  alt=""
                  onClick={() => {
                    this.props.navigate("/");
                  }}
                />
                <div
                  className="navbar-container main-menu-content"
                  data-menu="menu-container"
                >
                  <ul
                    className="nav navbar-nav"
                    id="main-menu-navigation"
                    data-menu="menu-navigation"
                  >
                    <li className="dropdown nav-item" data-menu="dropdown">
                      <a
                        className="dropdown-toggle nav-link"
                        href="#"
                        data-toggle="dropdown"
                      >
                        <span data-i18n="Others">Exams</span>
                        <i className="fa-solid fa-chevron-down" />
                      </a>

                      <ul className="dropdown-menu">
                        <li
                          className="dropdown dropdown-submenu"
                          data-menu="dropdown-submenu"
                        >
                          <a
                            className="dropdown-item dropdown-toggle"
                            href="#"
                            data-toggle="dropdown"
                          >
                            <span data-i18n="Tables">English</span>
                          </a>
                          <ul className="dropdown-menu">
                            <li
                              className="dropdown dropdown-submenu"
                              data-menu="dropdown-submenu"
                            >
                              <a
                                className="dropdown-item dropdown-toggle"
                                href="#"
                                data-toggle="dropdown"
                              >
                                <span data-i18n="DataTables Ext.">
                                  DataTables Ext.
                                </span>
                              </a>
                              <ul className="dropdown-menu">
                                <li data-menu="">
                                  <a
                                    className="dropdown-item"
                                    href="dt-extensions-autofill.html"
                                    data-toggle=""
                                  >
                                    <span data-i18n="AutoFill">AutoFill</span>
                                  </a>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
                <div className="user col d-flex justify-content-end">
                  <div className="d-none d-lg-flex align-items-center">
                    {this.context.user.subscription_expire > 0 ? (
                      <Link to="/pricingtable" className="header-count-box">
                        Your Subscription will expire in{" "}
                        <strong>
                          {this.context.user.subscription_expire} day
                          {this.context.user.subscription_expire > 1 ? "s" : ""}
                        </strong>
                      </Link>
                    ) : (
                      <Link to="/pricingtable" className="header-expire-box">
                        Your Subscription has expired
                      </Link>
                    )}

                    <Link to="/pricingtable">
                      <a className="header-count-box">
                        Exams : <span>{this.context.user.exam}</span>
                      </a>
                    </Link>
                    <Link to="/pricingtable">
                      <a className="header-count-box">
                        Subjects : <span>{this.context.user.subject}</span>
                      </a>
                    </Link>
                    <Link to="/pricingtable">
                      <a className="header-count-box">
                        Board : <span>{this.context.user.board}</span>
                      </a>
                    </Link>
                  </div>
                </div>
                <div className=" d-flex align-items-center justify-content-end">
                  <ul className="nav navbar-nav float-end">
                    <li className="dropdown dropdown-user nav-item">
                      <a
                        className="dropdown-toggle nav-link dropdown-user-link"
                        data-toggle="dropdown"
                      >
                        <span className="mr-1 user-name text-bold-700">
                          {this.context.user.first_name}{" "}
                          {this.context.user.last_name}
                        </span>
                        <span className="avatar avatar-online">
                          <img
                            src="../../../app-assets/images/portrait/small/avatar-s-19.png"
                            alt="avatar"
                          />
                        </span>
                      </a>
                      <div className="dropdown-menu dropdown-menu-right">
                        <NavLink to="/user-profile">
                          <a className="dropdown-item">User Profile</a>
                        </NavLink>
                        <NavLink to="/subscription">
                          <a className="dropdown-item">Subscription History</a>
                        </NavLink>
                        <p className="d-lg-none d-flex dropdown-item">
                          Your Stats:
                        </p>
                        <div
                          className="d-lg-none d-flex align-items-center flex-column"
                          style={{
                            gap: "10px",
                          }}
                        >
                          {this.context.user.subscription_expire > 0 ? (
                            <Link
                              to="/pricingtable"
                              className="header-count-box"
                            >
                              Your Subscription will expire in{" "}
                              <strong>
                                {this.context.user.subscription_expire} day
                                {this.context.user.subscription_expire > 1
                                  ? "s"
                                  : ""}
                              </strong>
                            </Link>
                          ) : (
                            <Link
                              to="/pricingtable"
                              className="header-expire-box"
                            >
                              Your Subscription has expired
                            </Link>
                          )}

                          <Link
                            to="/pricingtable"
                            className="header-count-box d-block w-75"
                          >
                            Exams : <span>{this.context.user.exam}</span>
                          </Link>
                          <Link
                            to="/pricingtable"
                            className="header-count-box d-block w-75"
                          >
                            Subjects : <span>{this.context.user.subject}</span>
                          </Link>
                          <Link
                            to="/pricingtable"
                            className="header-count-box d-block w-75"
                          >
                            Board : <span>{this.context.user.board}</span>
                          </Link>
                        </div>
                        <a
                          className="dropdown-item"
                          onClick={() => this.logout()}
                        >
                          Sign Out
                        </a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </nav> */}
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
          <img
            src={logo}
            className="main-logo cursor-pointer"
            alt=""
            onClick={() => {
              this.props.navigate("/");
            }}
          />
          <button
            className="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span className="navbar-toggler-icon" />
          </button>

          <div className="collapse navbar-collapse" id="navbarNavDropdown">
            <ul className="navbar-nav">
              <li className="nav-item dropdown">
                <a
                  className="nav-link dropdown-toggle ml-5"
                  href="#"
                  id="navbarDropdownMenuLink"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Exams
                  <i className="fa-solid fa-chevron-down" />
                </a>

                <ul
                  className="dropdown-menu"
                  aria-labelledby="dropdownMenuButton"
                >
                  {this.state.main_examList.map((item, index) => {
                    return (
                      <li>
                        <a
                          className="dropdown-item"
                          href="#"
                          onMouseEnter={() => {
                            this.setState({
                              exam_id: item.id,
                            });
                          }}
                        >
                          {item.exam_name}
                        </a>
                        <ul className="dropdown-menu dropdown-submenu">
                          {item.subjects.map((values) => {
                            return (
                              <li
                                className="dropdown-item"
                                onMouseEnter={() => {
                                  this.setState({
                                    subject_id: values.id,
                                  });
                                }}
                              >
                                {values.subject_name}

                                <ul className="dropdown-menu dropdown-submenu">
                                  {values.boards.map((value) => {
                                    return (
                                      <li
                                        className="dropdown-item cursor-pointer"
                                        onClick={() => {
                                          this.props.navigate(
                                            "/subtopic/" +
                                              this.state.exam_id +
                                              "/" +
                                              this.state.subject_id +
                                              "/" +
                                              value.id
                                          );
                                        }}
                                      >
                                        {value.board_name}
                                      </li>
                                    );
                                  })}
                                </ul>
                              </li>
                            );
                          })}
                        </ul>
                      </li>
                    );
                  })}
                </ul>
              </li>
            </ul>
            <div className="user col d-flex justify-content-end">
              <div className="d-none d-lg-flex align-items-center">
                {this.context.user.subscription_expire > 0 ? (
                  <Link to="/pricingtable" className="header-count-box">
                    Your Subscription will expire in{" "}
                    <strong>
                      {this.context.user.subscription_expire} day
                      {this.context.user.subscription_expire > 1 ? "s" : ""}
                    </strong>
                  </Link>
                ) : (
                  <Link
                    to="/pricingtable"
                    className="header-expire-box text-danger"
                  >
                    Your Subscription has expired 
                  </Link>
                )}

                <Link to="/pricingtable">
                  <a className="header-count-box">
                    Exams : <span>{this.context.user.exam}</span>
                  </a>
                </Link>
                <Link to="/pricingtable">
                  <a className="header-count-box">
                    Subjects : <span>{this.context.user.subject}</span>
                  </a>
                </Link>
                <Link to="/pricingtable">
                  <a className="header-count-box">
                    Board : <span>{this.context.user.board}</span>
                  </a>
                </Link>
              </div>
            </div>
            {/* <div className=" d-flex align-items-center justify-content-end">
              <ul className="nav navbar-nav float-end">
                <li className="dropdown dropdown-user nav-item">
                  <a
                    className="dropdown-toggle nav-link dropdown-user-link"
                    data-toggle="dropdown"
                  >
                    <span className="mr-1 user-name text-bold-700">
                      {this.context.user.first_name}{" "}
                      {this.context.user.last_name}
                    </span>
                    <span className="avatar avatar-online">
                      <img
                        src="../../../app-assets/images/portrait/small/avatar-s-19.png"
                        alt="avatar"
                      />
                    </span>
                  </a>
                  <div className="dropdown-menu dropdown-menu-right">
                    <NavLink to="/user-profile">
                      <a className="dropdown-item">User Profile</a>
                    </NavLink>
                    <NavLink to="/subscription">
                      <a className="dropdown-item">Subscription History</a>
                    </NavLink>
                    <p className="d-lg-none d-flex dropdown-item">
                      Your Stats:
                    </p>
                    <div
                      className="d-lg-none d-flex align-items-center flex-column"
                      style={{
                        gap: "10px",
                      }}
                    >
                      {this.context.user.subscription_expire > 0 ? (
                        <Link to="/pricingtable" className="header-count-box">
                          Your Subscription will expire in{" "}
                          <strong>
                            {this.context.user.subscription_expire} day
                            {this.context.user.subscription_expire > 1
                              ? "s"
                              : ""}
                          </strong>
                        </Link>
                      ) : (
                        <Link to="/pricingtable" className="header-expire-box">
                          Your Subscription has expired
                        </Link>
                      )}

                      <Link
                        to="/pricingtable"
                        className="header-count-box d-block w-75"
                      >
                        Exams : <span>{this.context.user.exam}</span>
                      </Link>
                      <Link
                        to="/pricingtable"
                        className="header-count-box d-block w-75"
                      >
                        Subjects : <span>{this.context.user.subject}</span>
                      </Link>
                      <Link
                        to="/pricingtable"
                        className="header-count-box d-block w-75"
                      >
                        Board : <span>{this.context.user.board}</span>
                      </Link>
                    </div>
                    <a className="dropdown-item" onClick={() => this.logout()}>
                      Sign Out
                    </a>
                  </div>
                </li>
              </ul>
            </div> */}

            <div className="dropdown">
              <a
                className="dropdown-toggle nav-link dropdown-user-link"
                data-toggle="dropdown"
              >
                <span className="mr-1 user-name text-bold-700">
                  {this.context.user.first_name} {this.context.user.last_name}
                </span>
                <img src={user} className="avatar avatar-online" alt="avatar" />
              </a>
              <div className="dropdown-menu dropdown-menu-right">
                <NavLink to="/user-profile">
                  <a className="dropdown-item">User Profile</a>
                </NavLink>
                <NavLink to="/subscription">
                  <a className="dropdown-item">Subscription History</a>
                </NavLink>
                <p className="d-lg-none d-flex dropdown-item">Your Stats:</p>
                <div
                  className="d-lg-none d-flex align-items-center flex-column"
                  style={{
                    gap: "10px",
                  }}
                >
                  {this.context.user.subscription_expire > 0 ? (
                    <Link to="/pricingtable" className="header-count-box">
                      Your Subscription will expire in{" "}
                      <strong>
                        {this.context.user.subscription_expire} day
                        {this.context.user.subscription_expire > 1 ? "s" : ""}
                      </strong>
                    </Link>
                  ) : (
                    <Link to="/pricingtable" className="header-expire-box">
                      Your Subscription has expired
                    </Link>
                  )}

                  <Link
                    to="/pricingtable"
                    className="header-count-box d-block w-75"
                  >
                    Exams : <span>{this.context.user.exam}</span>
                  </Link>
                  <Link
                    to="/pricingtable"
                    className="header-count-box d-block w-75"
                  >
                    Subjects : <span>{this.context.user.subject}</span>
                  </Link>
                  <Link
                    to="/pricingtable"
                    className="header-count-box d-block w-75"
                  >
                    Board : <span>{this.context.user.board}</span>
                  </Link>
                </div>
                <a className="dropdown-item" onClick={() => this.logout()}>
                  Sign Out
                </a>
              </div>
            </div>
          </div>
        </nav>
      </>
    );
  }
}

function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <Header {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
