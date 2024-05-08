import React, { Component } from 'react';
import Header from '../components/Header';
import Highcharts from 'highcharts/highstock';
import { useLocation, useParams, useNavigate } from 'react-router-dom';
import { Modal } from 'react-responsive-modal';
import 'react-responsive-modal/styles.css';
import { api } from '../config';
import HighchartsReact from 'highcharts-react-official';
import mathques from '../assets/images/mathques.png';
import lastques from '../assets/images/lastques.png';
import totalquess from '../assets/images/totalquess.png';
import improvement from '../assets/images/improvement.png';
import suggestion from '../assets/images/sugges.png';
import { Link } from 'react-router-dom';
import { toast } from 'react-toastify';
import { AuthContext } from '../AuthContextProvider';
import Loading from '../components/Loading';

export class Home extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      isloading: true,
      modalOpen: false,
      examList: [],
      main_examList: [],
      subjectBoardData: [],
      subjectId: '',
      examId: '',
      open: false,
      dashbord_data: {},
    };
    this.chartOptions1 = {
      title: {
        text: 'Total Revenue Generated',
      },
      xAxis: {
        categories: [
          'Jan',
          'Feb',
          'Mar',
          'Apr',
          'May',
          'Jun',
          'Jul',
          'Aug',
          'Sep',
          'Oct',
          'Nov',
          'Dec',
        ],
      },
      series: [
        {
          name: 'Data',
          data: [
            29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1,
            295.6, 454.4,
          ],
        },
      ],
    };

    this.chartOptions2 = {
      chart: {
        type: 'column',
      },
      title: {
        text: 'Top Selling Exam',
      },
      xAxis: {
        categories: [
          'Jan',
          'Feb',
          'Mar',
          'Apr',
          'May',
          'Jun',
          'Jul',
          'Aug',
          'Sep',
          'Oct',
          'Nov',
          'Dec',
        ],
      },
      yAxis: {
        title: {
          text: 'Value',
        },
      },
      series: [
        {
          name: 'Data',
          data: [
            29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1,
            295.6, 454.4,
          ],
        },
      ],
    };
  }

  componentDidMount() {
    this.fetchExams();
  }

  fetchExams = () => {
    fetch(api + 'fetch_exam', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ examList: json.data });
        } else {
          toast.error(json.message);
          this.setState({ examList: [] });
        }
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        this.fetchData();
      });
  };

  fetchData = () => {
    fetch(api + 'fetch_user_exam_data', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ main_examList: json.data });
          this.setState({ subjectBoardData: json.data[0].subjects });
        } else {
          // toast.error(json.message);
          this.setState({ main_examList: [], subjectBoardData: [] });
        }
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        this.fetchDashboard();
      });
  };

  fetchDashboard = () => {
    fetch(api + 'fetch_dashboard', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((json) => {
        if (json.status) {
          this.setState({ dashbord_data: json });
        } else {
          // toast.error(json.message);
          this.setState({ dashbord_data: {} });
        }
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        this.setState({ isloading: false });
      });
  };

  getUserStatusClass = (status) => {
    switch (status) {
      case 'active':
        return 'badge-success';
      case 'inactive':
        return 'badge-warning';
      case 'pending':
        return 'badge-primary';
      default:
        break;
    }
  };

  onOpenModal = () => {
    this.setState({ open: true });
  };

  onCloseModal = () => {
    this.setState({ open: false });
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
              <div className="content-header row"></div>
              <div className="content-body">
                {this.state.main_examList.length > 0 && (
                  <div className="row mt-5">
                    <div className="col-md-12 mb-2">
                      <h3 className="dash-heading">Exams Enrolled In</h3>
                    </div>
                    {this.state.main_examList.map((card, index) => (
                      <div key={index} className="col-md-3">
                        <div
                          className={
                            index % 2 === 0
                              ? 'card pull-up cursor-pointer bg-gradient-directional-danger'
                              : 'card pull-up cursor-pointer bg-gradient-directional-success'
                          }
                          // onClick={this.onOpenModal}
                          onClick={() => {
                            this.setState({ modalOpen: true });
                            this.setState({ subjectBoardData: card.subjects });
                            this.setState({ examId: card.id });
                          }}
                        >
                          <div className="card-content">
                            <div className="card-body">
                              <div className="media d-flex">
                                <div className="media-body text-left">
                                  <div className="media-body d-flex align-items-center justify-content-center">
                                    <h3 className="text-white">
                                      {card.exam_name}
                                    </h3>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                )}
                <div className="row mt-5">
                  <div className="col-md-12 mb-2">
                    <h3 className="dash-heading">My Stats</h3>
                  </div>
                  <div className="col-md-4">
                    <div className="card pull-up">
                      <div className="card-content">
                        <div className="card-body">
                          <div className="media d-flex">
                            <div className="media-body text-left">
                              <h6 className="dash-heading1">
                                Total Number Of Questions Attempted
                              </h6>
                              <h3 className="info dash-sub-heading">
                                {this.state.dashbord_data.total}
                              </h3>
                            </div>
                            <div>
                              <img
                                src={mathques}
                                className="card-image float-right"
                                alt=""
                              />
                            </div>
                          </div>
                          <div className="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div
                              className="progress-bar bg-gradient-x-info"
                              role="progressbar"
                              style={{ width: '80%' }}
                              aria-valuenow={80}
                              aria-valuemin={0}
                              aria-valuemax={100}
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="col-md-4">
                    <div className="card pull-up">
                      <div className="card-content">
                        <div className="card-body">
                          <div className="media d-flex">
                            <div className="media-body text-left">
                              <h6 className="dash-heading1">
                                Total Number Of Questions Marked for Revisit
                              </h6>
                              <h3 className="info dash-sub-heading">
                                {this.state.dashbord_data.revisit}
                              </h3>
                            </div>
                            <div>
                              <img
                                src={lastques}
                                className="card-image float-right"
                                alt=""
                              />
                            </div>
                          </div>
                          <div className="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div
                              className="progress-bar bg-gradient-x-warning"
                              role="progressbar"
                              style={{ width: '65%' }}
                              aria-valuenow={65}
                              aria-valuemin={0}
                              aria-valuemax={100}
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="col-md-4">
                    <div className="card pull-up">
                      <div className="card-content">
                        <div className="card-body">
                          <div className="media d-flex">
                            <div className="media-body text-left">
                              <h6 className="dash-heading1">
                                Total Number Of Questions Marked as Completed
                              </h6>
                              <h3 className="info dash-sub-heading">
                                {this.state.dashbord_data.completed}
                              </h3>
                            </div>
                            <div>
                              <img
                                src={totalquess}
                                className="card-image float-right"
                                alt=""
                              />
                            </div>
                          </div>
                          <div className="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div
                              className="progress-bar bg-gradient-x-success"
                              role="progressbar"
                              style={{ width: '75%' }}
                              aria-valuenow={75}
                              aria-valuemin={0}
                              aria-valuemax={100}
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                {/* <div className="row mt-5">
                  <div className="col-md-12 mb-2">
                    <h3 className="dash-heading">Performance</h3>
                  </div>
                  <div className="col-md-6">
                    <div className="card">
                      <div className="card-body">
                        <HighchartsReact
                          highcharts={Highcharts}
                          options={this.chartOptions1}
                          containerProps={{ style: { height: "400px" } }}
                        />
                      </div>
                    </div>
                  </div>
                  <div className="col-md-6">
                    <div className="card">
                      <div className="card-body">
                        <HighchartsReact
                          highcharts={Highcharts}
                          options={this.chartOptions2}
                          containerProps={{ style: { height: "400px" } }}
                        />
                      </div>
                    </div>
                  </div>
                </div> */}
                {this.state.examList.length > 0 && (
                  <div className="row mt-5">
                    <div className="col-md-12 mb-2">
                      <h3 className="dash-heading">Shop Exams</h3>
                    </div>
                    {this.state.examList.map((card, index) => (
                      <div key={index} className="col-md-3">
                        <Link to={'/subject/' + card.id}>
                          <div
                            className={
                              index % 2 === 0
                                ? 'card pull-up cursor-pointer bg-gradient-directional-danger'
                                : 'card pull-up cursor-pointer bg-gradient-directional-success'
                            }
                          >
                            <div className="card-content">
                              <div className="card-body">
                                <div className="media d-flex">
                                  <div className="media-body text-left">
                                    <div className="media-body d-flex align-items-center justify-content-center">
                                      <h3 className="text-white">
                                        {' '}
                                        {card.exam_name}
                                      </h3>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </Link>
                      </div>
                    ))}
                  </div>
                )}
              </div>
            </div>
          </div>
        )}

        <Modal
          open={this.state.modalOpen}
          onClose={() => {
            this.setState({ modalOpen: false });
          }}
          center
          classNames={{
            modal: 'customModal',
          }}
        >
          <div classname="card">
            <div classname="card-content">
              <div classname="card-body">
                {this.state.subjectBoardData.map((item, index) => {
                  return (
                    <div className="accordion" id="accordionExample">
                      <div className="accordion-item">
                        <h2 className="accordion-header" id="headingTwo">
                          <button
                            className="accordion-button collapsed"
                            type="button"
                            data-toggle="collapse"
                            data-target={'#collapseTwo' + index}
                            aria-expanded="true"
                            aria-controls="collapseTwo"
                            onClick={() => {
                              this.setState({ subjectId: item.id });
                            }}
                          >
                            {item.subject_name}
                          </button>
                        </h2>
                        <div
                          id={'collapseTwo' + index}
                          className="accordion-collapse collapse"
                          aria-labelledby="headingOne"
                          data-parent="#accordionExample"
                        >
                          <div className="accordion-body">
                            <div className="row">
                              {item.boards.length > 0 &&
                                item.boards.map((board) => {
                                  return (
                                    <div className="col-md-3 mob-view">
                                      <Link
                                        to={
                                          '/subtopic/' +
                                          this.state.examId +
                                          '/' +
                                          this.state.subjectId +
                                          '/' +
                                          board.id
                                        }
                                      >
                                        <button
                                          type="button"
                                          className="btn btn-danger btn-min-width "
                                        >
                                          {board.board_name}
                                        </button>
                                      </Link>
                                    </div>
                                  );
                                })}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </Modal>
      </>
    );
  }
}

function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <Home {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
