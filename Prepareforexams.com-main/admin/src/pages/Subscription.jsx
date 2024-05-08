import moment from 'moment';
import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { AuthContext } from '../AuthContextProvider';
import no_tranactions from '../assets/images/no_tranactions.webp';
import Header from '../components/Header';
import Loading from '../components/Loading';
import { api } from '../config';

export class Subscription extends Component {
  static contextType = AuthContext;
  state = {
    isloading: true,
    loading: true,
    userList: [],
  };

  defaultSorted = [
    {
      dataField: 'name',
      order: 'asc',
    },
  ];

  componentDidMount() {
    this.fetchSubscriptions();
  }

  fetchSubscriptions = () => {
    fetch(api + 'fetch_subscription_data', {
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
          this.setState({ userList: json.data });
        } else {
          this.setState({ userList: [] });
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
        <Header />
        {this.state.isloading ? (
          <Loading />
        ) : (
          <div className="app-content content">
            <div className="content-overlay" />
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
                          &nbsp; | Subscription
                        </li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
              {this.state.userList.length > 0 ? (
                <div className="content-body">
                  <div className="row">
                    {this.state.userList.map((item, index) => {
                      return (
                        <div className="col-md-6">
                          <div className="card">
                            <div className="card-header pb-0">
                              <span className="card-title">
                                Subscription Name:{' '}
                                <strong>{item.plan.subscription_name}</strong>
                              </span>
                              <span className="float-right primary">
                                {' '}
                                {item.txn_status === 'success' ? (
                                  <span className="badge badge-success">
                                    Success
                                  </span>
                                ) : (
                                  <span className="badge badge-danger">
                                    Failed
                                  </span>
                                )}
                              </span>
                            </div>
                            <div className="card-content collapse show">
                              <div className="card-body">
                                <p>Transaction Id :{item.txn_id}</p>
                                <div className="row">
                                  <div className="col-md-4">
                                    <p>
                                      Amount :{' '}
                                      <strong>$ {item.txn_amount}</strong>
                                    </p>
                                  </div>
                                  <div className="col-md-4">
                                    <p>
                                      Exam Count :{' '}
                                      <strong>{item.plan.no_of_exam}</strong>
                                    </p>
                                  </div>
                                  <div className="col-md-4">
                                    <p>
                                      Subject Count :{' '}
                                      <strong>{item.plan.no_of_subject}</strong>
                                    </p>
                                  </div>
                                  <div className="col-md-4">
                                    <p>
                                      Board Count :{' '}
                                      <strong>{item.plan.no_of_board}</strong>
                                    </p>
                                  </div>
                                  <div className="col-md-4">
                                    <p>
                                      TXN Date :{' '}
                                      <strong>
                                        {' '}
                                        {moment(item.updated_at).format(
                                          'DD/MM/YYYY'
                                        )}
                                      </strong>
                                    </p>
                                  </div>
                                  <div className="col-md-4">
                                    <p>
                                      Validity :{' '}
                                      <strong>
                                        {item.plan.sv_month}{' '}
                                        {item.plan.sv_month > 1
                                          ? 'Months'
                                          : 'Month'}
                                      </strong>
                                    </p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                </div>
              ) : (
                <div
                  className="row"
                  style={{
                    display: 'flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    flexDirection: 'column',
                    height: '80vh',
                    width: '100%',
                  }}
                >
                  <div className="text-center">
                    <img
                      src={no_tranactions}
                      alt=""
                      style={{
                        width: '300px',
                      }}
                    />
                    <h3>No Transactions Found</h3>
                  </div>
                </div>
              )}
            </div>
          </div>
        )}
      </>
    );
  }
}

export default Subscription;
