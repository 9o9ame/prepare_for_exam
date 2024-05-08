import React, { Component } from 'react';
import Header from '../components/Header';
import { Modal } from 'react-responsive-modal';
import 'react-responsive-modal/styles.css';
import { Link, useLocation, useNavigate, useParams } from 'react-router-dom';
import { api, pdf } from '../config';
import { AuthContext } from '../AuthContextProvider';
import Loading from '../components/Loading';
import no_question from '../assets/images/no_question.webp';
import HTMLReactParser from 'html-react-parser';

export class Teacherquestion extends Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      liked: false,
      question: true,
      question_id: '',
      question_data: [],
      single_question: [],
      loading: true,
      active_cat: 'easy',
      questionLoading: false,
      modalOpen: false,
      selectedQuestion: [],
      liked: false,
      question: true,
      status: false,
      isloading: true,
      fullQuestion: '',
      modalOpen: false,
      selectedQuestion: [],
      generatePdfButton: false,
    };
  }

  componentDidMount() {
    this.fetchQuestion('easy');
    var selectedQuestion = JSON.parse(localStorage.getItem('selectedQuestion'));
    if (selectedQuestion != null) {
      this.setState({ selectedQuestion: selectedQuestion });
    }
  }

  componentDidUpdate(prevProps) {
    if (this.props !== prevProps) {
      this.fetchQuestion('easy');
    }
  }

  change_cat = (cat) => {
    this.setState({ active_cat: cat, questionLoading: true });
    this.fetchQuestion(cat);
  };

  fetchQuestion = (type) => {
    fetch(api + 'fetch-question', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        exam_id: this.props.exam,
        subject_id: this.props.subject,
        board_id: this.props.board,
        topic: this.props.topic,
        subtopic: this.props.subtopic,
        question_type: type,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (!json.status) {
          this.setState({ question_data: [], questionLoading: false });
        } else {
          this.setState({ question_data: json.data, questionLoading: false });
        }
        return json;
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ isloading: false, questionLoading: false });
      });
  };

  generatePdf = () => {
    this.setState({ generatePdfButton: true });
    fetch(api + 'generate_pdf', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        question_id: this.state.selectedQuestion,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (!json.status) {
          this.setState({ generatePdfButton: false });
        } else {
          this.setState({ generatePdfButton: true });
          window.open(pdf + json.pdf, '_blank');
          localStorage.removeItem('selectedQuestion');
        }
        return json;
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ generatePdfButton: false });
      });
  };

  fullQuestionViewTeacher = (id, index) => {
    this.setState({ fullQuestion: id, modalOpen: true });
  };

  render() {
    return (
      <>
        <Header />
        {this.state.isloading ? (
          <Loading />
        ) : (
          <div className="app-content container-fluid center-layout mt-2">
            <div className="content-overlay" />
            <div className="content-wrapper">
              <div className="content-header row">
                <div className="content-header-left col-md-6 col-12 mb-2">
                  <div className="row breadcrumbs-top d-block">
                    <div className="breadcrumb-wrapper col-12">
                      <ol className="breadcrumb bread-heading">
                        <Link to="/">
                          <li className="breadcrumb-item">
                            <a>Dashboard</a>
                          </li>
                        </Link>
                        <li className="breadcrumb-item active">
                          &nbsp; | Question
                        </li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
              <div className="content-body">
                <section id="basic-form-layouts">
                  <div className="row match-height">
                    <div className="col-md-12">
                      <div className="card">
                        <div className="card-content collapse show">
                          <div className="card-body">
                            <div className="row">
                              <div className="col-md-6 col-sm-12">
                                <button
                                  type="button"
                                  className={
                                    'btn mr-1 ' +
                                    (this.state.active_cat === 'easy'
                                      ? 'btn-success'
                                      : 'btn-outline-success')
                                  }
                                  onClick={() => {
                                    this.change_cat('easy');
                                  }}
                                >
                                  Easy
                                </button>
                                <button
                                  type="button"
                                  className={
                                    'btn mr-1 ' +
                                    (this.state.active_cat === 'medium'
                                      ? 'btn-warning'
                                      : 'btn-outline-warning')
                                  }
                                  onClick={() => {
                                    this.change_cat('medium');
                                  }}
                                >
                                  Medium
                                </button>
                                <button
                                  type="button"
                                  className={
                                    'btn mr-1 ' +
                                    (this.state.active_cat === 'hard'
                                      ? 'btn-danger'
                                      : 'btn-outline-danger')
                                  }
                                  onClick={() => {
                                    this.change_cat('hard');
                                  }}
                                >
                                  Hard
                                </button>
                              </div>
                              {this.state.selectedQuestion.length > 0 && (
                                <div className="col-md-6 d-flex justify-content-end align-items-center">
                                  {this.state.generatePdfButton ? (
                                    <button
                                      type="button"
                                      className="btn btn-outline-primary btn-sm"
                                      disabled
                                    >
                                      <span
                                        className="spinner-border spinner-border-sm"
                                        style={{ marginRight: '5px' }}
                                        role="status"
                                        aria-hidden="true"
                                      ></span>
                                      Generating...
                                    </button>
                                  ) : (
                                    <button
                                      type="button"
                                      className="btn btn-outline-primary btn-sm"
                                      onClick={() => {
                                        // console.log(this.state.selectedQuestion);
                                        this.generatePdf();
                                      }}
                                    >
                                      Generate PDF
                                    </button>
                                  )}
                                </div>
                              )}
                            </div>
                            <div className="row mt-5">
                              {!this.state.questionLoading ? (
                                this.state.question_data.length > 0 ? (
                                  this.state.question_data.map(
                                    (item, index) => {
                                      return (
                                        <div className="col-12 cursor-pointer">
                                          <label className="checkbox checkbox-primary d-flex align-items-start w-100">
                                            <input
                                              type="checkbox"
                                              id="vehicle1"
                                              name="vehicle1"
                                              defaultValue="Bike"
                                              onChange={(e) => {
                                                if (e.target.checked) {
                                                  this.setState({
                                                    selectedQuestion: [
                                                      ...this.state
                                                        .selectedQuestion,
                                                      item.id,
                                                    ],
                                                  });
                                                  localStorage.setItem(
                                                    'selectedQuestion',
                                                    JSON.stringify([
                                                      ...this.state
                                                        .selectedQuestion,
                                                      item.id,
                                                    ])
                                                  );
                                                } else {
                                                  this.setState({
                                                    selectedQuestion:
                                                      this.state.selectedQuestion.filter(
                                                        (id) => id !== item.id
                                                      ),
                                                  });
                                                  localStorage.setItem(
                                                    'selectedQuestion',
                                                    JSON.stringify(
                                                      this.state.selectedQuestion.filter(
                                                        (id) => id !== item.id
                                                      )
                                                    )
                                                  );
                                                }
                                              }}
                                              checked={
                                                (JSON.parse(
                                                  localStorage.getItem(
                                                    'selectedQuestion'
                                                  )
                                                ) != null &&
                                                  JSON.parse(
                                                    localStorage.getItem(
                                                      'selectedQuestion'
                                                    )
                                                  ).includes(item.id)) ||
                                                this.state.selectedQuestion.includes(
                                                  item.id
                                                )
                                                  ? true
                                                  : false
                                              }
                                            />{' '}
                                            <div className="card bg-infos w-100 ms-4">
                                              <div className="card-content">
                                                <div className="card-body pb-1 cursor-pointer">
                                                  <h4 className="text-black mb-1">
                                                    Question {index + 1}
                                                  </h4>
                                                  <h6 className="text-black">
                                                    {HTMLReactParser(
                                                      item.question
                                                    )}
                                                  </h6>

                                                  <i
                                                    className="fa-solid fa-eye staricon stariconbg question-view-icon"
                                                    onClick={() => {
                                                      this.setState({
                                                        modalOpen: true,
                                                      });
                                                      this.fullQuestionViewTeacher(
                                                        item.question
                                                      );
                                                    }}
                                                  />
                                                </div>
                                              </div>
                                            </div>
                                          </label>
                                        </div>
                                      );
                                    }
                                  )
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
                                        src={no_question}
                                        alt=""
                                        style={{
                                          width: '300px',
                                        }}
                                      />
                                      <h3>No Question Found</h3>
                                    </div>
                                  </div>
                                )
                              ) : (
                                <Loading />
                              )}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
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
          <div className="row">
            <div className="col-xl-12 col-md-6 col-12">
              <div className="card bg-infos">
                <div className="card-content">
                  <div className="card-body pb-1">
                    <div className="row">
                      <div className="col-12">
                        <h6 className="text-black">
                          {HTMLReactParser(this.state.fullQuestion)}
                        </h6>
                      </div>
                    </div>
                  </div>
                </div>
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
    <Teacherquestion
      {...props}
      {...useParams()}
      navigate={abcd}
      location={location}
    />
  );
}

export default (props) => <Navigate {...props} />;
