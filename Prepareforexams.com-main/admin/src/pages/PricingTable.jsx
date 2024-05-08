import React, { Component } from 'react';
import Header from '../components/Header';
import { api } from '../config';
import { AuthContext } from '../AuthContextProvider';
import Loading from '../components/Loading';

export class PricingTable extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      isloading: true,
      user: '',
      token: '',
      loading: true,
      subscription: [],
      button_loading: false,
    };
  }
  componentDidMount() {
    this.fetch_subscription();
  }

  fetch_subscription = () => {
    fetch(api + 'fetch-subscription-panel', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({
            subscription: json.data,
          });
        } else {
          this.setState({
            subscription: [],
          });
        }
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ isloading: false });
      });
  };

  create_order = (id) => {
    this.setState({ button_loading: true });
    fetch(api + 'create_order_request', {
      method: 'POST',
      headers: {
        Application: 'application/json',
        'Content-Type': 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        subscription_id: id,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          window.location.href = json.url;
        } else {
          alert(json.message);
        }
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ button_loading: true });
      });
  };

  render() {
    return (
      <>
        <Header />
        {this.state.isloading ? (
          <Loading />
        ) : (
          <div className="main-content position-relative">
            <div className="p-3 p-xxl-5 bg-primary after-header">
              <div className="container-fluid px-0 pb-md-5 mb-md-5">
                <div className="row pb-5 mb-5">
                  <div className="col-md-10 col-lg-12 col-xxl-6 text-center p-4 pb-0 pricing p-md-5 mx-auto">
                    <h1 className="display-4 dash-headings text-white">
                      Pricing...
                    </h1>
                  </div>
                </div>
              </div>
            </div>
            <div className="px-3 px-xxl-5 pb-3 pb-xxl-5 pricing-cards">
              <div className="container-fluid px-0 top-less">
                <div className="tab-content">
                  <div
                    className="tab-pane fade show active"
                    id="pills-monthly"
                    role="tabpanel"
                  >
                    <div className="row">
                      <div className="col-md-4"></div>
                      <div className="col-md-4 d-flex align-items-center justify-content-center">
                        {this.state.subscription.map((item, index) => {
                          return (
                            <div className="card profile-card-with-cover pull-up w-100">
                              <div className="card-content card-deck text-center">
                                <div className="card ">
                                  <div className="card-header pb-0">
                                    <h2 className="my-0 font-weight-bold">
                                      {item.subscription_name}
                                    </h2>
                                  </div>
                                  <div className="card-body">
                                    <h1 className="pricing-card-title">
                                      ${item.subscription_price}
                                      <small className="text-muted">/ mo</small>
                                    </h1>
                                    <ul className="list-unstyled mt-2 mb-2">
                                      <li className=" font-weight-bold">
                                        Validity : {item.sv_month}{' '}
                                        {item.sv_month > 1 ? 'Months' : 'Month'}
                                      </li>
                                      <li className="font-weight-bold">
                                        No. Of Boards : {item.no_of_board}
                                      </li>
                                      <li className="font-weight-bold">
                                        No. Of Exams : {item.no_of_exam}
                                      </li>
                                      <li className=" font-weight-bold">
                                        No. Of Subjects : {item.no_of_subject}
                                      </li>
                                    </ul>
                                    {this.state.button_loading ? (
                                      //create a loading button
                                      <button
                                        type="button"
                                        className="btn btn-primary w-50 my-1 btn-rounded  rounded-pill mx-auto"
                                        disabled
                                        style={{
                                          cursor: 'not-allowed',
                                          opacity: '0.5',
                                        }}
                                      >
                                        <span>Please Wait...</span>
                                      </button>
                                    ) : (
                                      <button
                                        type="button"
                                        onClick={() => {
                                          this.create_order(item.id);
                                        }}
                                        className="btn btn-primary w-50 my-1 btn-rounded  rounded-pill mx-auto"
                                      >
                                        <span>Purchase</span>
                                      </button>
                                    )}
                                  </div>
                                </div>
                              </div>
                            </div>
                          );
                        })}
                      </div>

                      <div className="col-md-4"></div>
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

export default PricingTable;
