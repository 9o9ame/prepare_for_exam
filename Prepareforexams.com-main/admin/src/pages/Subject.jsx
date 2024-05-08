import React, { Component } from 'react';
import Header from '../components/Header';
import Swal from 'sweetalert2';
import { AuthContext } from '../AuthContextProvider';
import { api } from '../config';
import { useLocation, useNavigate, useParams } from 'react-router-dom';
import no_data from '../assets/images/no_data.png';
import Loading from '../components/Loading';
import { Link } from 'react-router-dom';

export class Subject extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.HandleClick = this.HandleClick.bind(this);
    this.state = {
      isloading: true,
      checked1: false,
      checked2: false,
      checked3: false,
      checked4: false,
      data: [],
      subjectlist: [],
      active_subject: 0,
      boards: [],
      cart: [],
      subject_id: 0,
      buttonIsloading: false,
    };
  }

  componentDidMount() {
    window.setTimeout(() => {
      window.scrollTo(0, 0);
    }, 0);
  }

  HandleClick() {
    Swal.fire({
      title: 'Are you sure?',
      text: 'You want to activate this subscription!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes!',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire(
          'Confirmed!',
          'Your subscrption has been activated!',
          'success'
        );
      }
    });
  }

  componentDidMount() {
    this.fetchExams();
  }

  fetchExams = () => {
    fetch(api + 'fetch_exam_data', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        exam_id: this.props.id,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ data: json.data });
          this.setState({ subjectlist: json.data[0].subjects });

          this.setState({ boards: json.data[0].subjects[0].boards });
        } else {
          this.setState({ examList: [] });
        }
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        this.setState({ isloading: false });
      });
  };

  add_cart = (board_id, i) => {
    //check if board is already added in cart or not if not then add it
    const { cart } = this.state;

    var subject_id = this.state.subjectlist[this.state.active_subject].id;

    var obj = this.state.boards;
    obj[i].is_added = !obj[i].is_added;
    this.setState({ boards: obj });

    var data = {
      exam_id: this.props.id,
      subject_id: subject_id,
      board_id: board_id,
    };

    const index = cart.findIndex((x) => x.board_id === board_id);
    if (index === -1) {
      cart.push(data);
    } else {
      cart.splice(index, 1);
    }
    this.setState({ cart: cart });
  };

  activateSubscription = () => {
    this.setState({ buttonIsloading: true });
    if (
      this.context.user.board == -1 ||
      this.context.user.board >= this.state.cart.length
    ) {
      fetch(api + 'activate_board', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: this.context.token,
        },
        body: JSON.stringify({
          board_id: this.state.cart,
        }),
      })
        .then((response) => response.json())
        .then((json) => {
          if (json.status) {
            this.setState({ cart: [] });
            this.context.get_vendor_profile(this.context.token);
            Swal.fire({
              title: 'Success',
              text: 'Board activated successfully',
              icon: 'success',
              confirmButtonText: 'Ok',
            }).then((result) => {
              if (result.isConfirmed) {
                this.props.navigate('/');
              }
            });
          } else {
            toast.error(json.message);
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.setState({ buttonIsloading: false });
        });
    } else {
      Swal.fire({
        title: "You don't have credits",
        text: 'Do you want to buy credits?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, buy it!',
      }).then((result) => {
        if (result.isConfirmed) {
          this.props.navigate('/pricingtable');
        }
      });
    }
  };

  handlepayment = (e) => {
    e.preventDefault();
    const { checked1, checked2, checked3, checked4 } = this.state;
    if (
      checked1 == false &&
      checked2 == false &&
      checked3 == false &&
      checked4 == false
    ) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please select atleast one subject',
      });
    } else {
      const { stripe, elements } = this.props;
      if (!stripe || !elements) {
        return;
      }
      const cardElement = elements.getElement(PaymentElement);
      stripe
        .createPaymentMethod({
          type: 'card',
          card: cardElement,
        })
        .then((result) => {
          if (result.error) {
            console.log(result.error.message);
          } else {
            console.log(result.paymentMethod.id);
          }
        });
    }
  };

  render() {
    return (
      <>
        <Header />
        {this.state.isloading ? (
          <Loading />
        ) : (
          <div className="app-content content">
            <div className="content-wrapper">
              <div className="content-body">
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
                            {' '}
                            &nbsp; | Subject
                          </li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="row">
                  <div className="col-md-12 d-flex justify-content-end">
                    {this.state.buttonIsloading ? (
                      <button
                        type="button"
                        // className="btn btn-primary w-50 my-1 btn-rounded  rounded-pill mx-auto"
                        className="text-capitalize  btn btn-primary rounded"
                        disabled
                        style={{
                          cursor: 'not-allowed',
                          opacity: '0.5',
                          marginLeft: '10px',
                        }}
                      >
                        <span>Please wait...</span>
                      </button>
                    ) : (
                      this.state.cart.length > 0 && (
                        <button
                          className="text-capitalize  btn btn-primary rounded "
                          style={{
                            marginLeft: '10px',
                          }}
                          onClick={() => {
                            Swal.fire({
                              title: 'Are you sure?',
                              text: 'You want to activate this subscription!',
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, activate it!',
                            }).then((result) => {
                              if (result.isConfirmed) {
                                this.activateSubscription();
                              }
                            });
                          }}
                        >
                          Activate Subscription {this.state.cart.length}
                        </button>
                      )
                    )}
                  </div>
                </div>

                <div className="row all-contacts">
                  <div className="col-md-3">
                    <h1 className="dash-heading my-3">Subjects</h1>
                    <div className="subject-scroll">
                      <div className="card1">
                        <div className="card-content">
                          <div className="card-body">
                            <div className="row mt-1">
                              {this.state.subjectlist.length > 0 ? (
                                this.state.subjectlist.map((item, index) => (
                                  <div
                                    className="col-md-12 cursor-pointer "
                                    onClick={() => {
                                      this.setState({ active_subject: index });
                                      this.setState({ boards: item.boards });
                                    }}
                                  >
                                    <div className="card pull-up">
                                      <div className="card-content">
                                        <div className="card-body">
                                          <div className="media d-flex">
                                            <div className="media-body text-left">
                                              <h3 className="info text-center subject-name">
                                                {item.subject_name}
                                              </h3>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                ))
                              ) : (
                                <div
                                  className="col-md-12"
                                  style={{
                                    display: 'flex',
                                    flexDirection: 'column',
                                    justifyContent: 'center',
                                    alignItems: 'center',
                                  }}
                                >
                                  <img
                                    src={no_data}
                                    alt=""
                                    style={{
                                      width: '300px',
                                    }}
                                  />
                                  <h6 className="text-capitalize">
                                    No Subjects Found
                                  </h6>
                                </div>
                              )}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="col-md-9">
                    <h1 className="dash-heading my-3">Boards</h1>
                    <div className="subject-scroll">
                      <div className="card1">
                        <div className="card-content">
                          <div className="card-body">
                            {this.state.boards.length > 0 ? (
                              <div className="row mt-1">
                                {this.state.boards.map((i, index) => (
                                  <div className="col-xl-3 col-lg-6 col-12 cursor-pointer ">
                                    <div
                                      className="card pull-up"
                                      style={{
                                        backgroundColor: i.is_added
                                          ? '#f8d7da'
                                          : '#fff',
                                      }}
                                      onClick={() => {
                                        this.add_cart(i.id, index);
                                      }}
                                    >
                                      <div className="card-content">
                                        <div className="card-body">
                                          <div className="media d-flex">
                                            <div className="media-body text-left">
                                              <h3 className="info text-center subject-name">
                                                {i.board_name}
                                              </h3>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                ))}
                              </div>
                            ) : (
                              <div className="row">
                                <div
                                  className="col-md-12"
                                  style={{
                                    display: 'flex',
                                    flexDirection: 'column',
                                    justifyContent: 'center',
                                    alignItems: 'center',
                                  }}
                                >
                                  <img
                                    src={no_data}
                                    alt=""
                                    style={{
                                      width: '300px',
                                    }}
                                  />
                                  <h6 className="text-capitalize">
                                    No Boards Found
                                  </h6>
                                </div>
                              </div>
                            )}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </>
    );
  }
}

class CheckoutForm extends React.Component {
  render() {
    return (
      <div className="container">
        <div className="row">
          <div className="col-md-12">
            <h3 className="text-capitalize">Checkout</h3>
          </div>
        </div>
        <div className="row">
          <div className="col-md-6">
            <div className="card">
              <div className="card-body">
                <h5 className="card-title">Card title</h5>
                <p className="card-text">
                  With supporting text below as a natural lead-in to additional
                  content.
                </p>
                <a href="#" className="btn btn-primary">
                  Go somewhere
                </a>
              </div>
            </div>
          </div>
          <div className="col-md-6">
            <div className="card">
              <div className="card-body">
                <h5 className="card-title">Card title</h5>
                <p className="card-text">
                  With supporting text below as a natural lead-in to additional
                  content.
                </p>
                <a href="#" className="btn btn-primary">
                  Go somewhere
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <Subject {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
