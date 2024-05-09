import React, { Component } from 'react';
import Header from '../components/Header';
import { Link, useLocation, useNavigate, useParams } from 'react-router-dom';
import { api } from '../config';
import { AuthContext } from '../AuthContextProvider';
import no_question from '../assets/images/no_question.webp';
import Parser from 'html-react-parser';
import HTMLReactParser from 'html-react-parser';
import Loading from '../components/Loading';
import { toast } from 'react-toastify';

export class Question extends Component {
  static contextType = AuthContext;
  state = {
    isloading: true,
    is_loading: false,
    show_markup: false,
    old_password: '',
    new_password: '',
    confirm_password: '',
    questionListVisible: true,
    question: true,
    revisionnotes: false,
    liked: false,
    question_id: '',
    question_data: [],
    single_question: [],
    loading: true,
    fullQuestionView: false,
    active_cat: 'easy',
    current_question: [],
    questionLoading: false,
    currentIndex: 0,
  };

  componentDidMount() {
    this.fetchQuestion('easy');
  }

  fullQuestionView = (id, index, third) => {
    // console.log(this.state);
    // console.log(id);
    this.setState({ current_question: id });
    if (index !== 'next_question') {
      this.setState({ questionListVisible: !this.state.questionListVisible });
    }
    this.fetchQuestion(this.state.active_cat);
  };

   // Inside QuestionDetail component
   handleNextPrevious = (handleNextPrevious) => {
    const current_id = this.state.current_question.id;
    const all_questions = this.state.question_data;
  
    const current_index = all_questions.findIndex(function (e) {
      return e.id == current_id;
    });
    if (handleNextPrevious == 'next') {
      if (current_index + 1 < all_questions.length) {
        const next_question = all_questions[current_index + 1];
        this.fullQuestionView(
          next_question,
          'next_question',
          all_questions[current_index + 1]
        );
      } else {
        alert('Thanks for completing the questions!');
      }
    }else if (handleNextPrevious == 'previous') {
      if (current_index - 1 >= 0) {
        const next_question = all_questions[current_index - 1];
        this.fullQuestionView(
          next_question,
          'next_question',
          all_questions[current_index - 1]
        );
      } else {
        alert('Thanks for completing the questions!');
      }
     
    }
  };
  

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
        'Content-Type': 'application/json',
        Accept: 'application/json',
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

  render() {
    const path =
      '/subtopic/' +
      this.props.exam +
      '/' +
      this.props.subject +
      '/' +
      this.props.board;
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
                          {' '}
                          &nbsp; | Question
                        </li>
                      </ol>
                    </div>
                  </div>
                </div>
              </div>
              {this.state.questionListVisible ? (
                <QuestionList
                  change_cat={this.change_cat}
                  active_cat={this.state.active_cat}
                  question_data={this.state.question_data}
                  fullQuestionView={this.fullQuestionView}
                  questionLoading={this.state.questionLoading}
                />
              ) : (
                <QuestionDetail
                  fullQuestionView={this.fullQuestionView}
                  question={this.state.current_question}
                  handleNextPrevious={this.handleNextPrevious} // Pass handleNextPrevious method to QuestionDetail component
                />
              )}
            </div>
          </div>
        )}
      </>
    );
  }
}

class QuestionList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      revisionnotes: false,
    };
  }
  render() {
    return (
      <div className="content-body">
        {/* Basic form layout section start */}
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
                            (this.props.active_cat === 'easy'
                              ? 'btn-success'
                              : 'btn-outline-success')
                          }
                          onClick={() => {
                            this.props.change_cat('easy');
                          }}
                        >
                          Easy
                        </button>
                        <button
                          type="button"
                          className={
                            'btn mr-1 ' +
                            (this.props.active_cat === 'medium'
                              ? 'btn-warning'
                              : 'btn-outline-warning')
                          }
                          onClick={() => {
                            this.props.change_cat('medium');
                          }}
                        >
                          Medium
                        </button>
                        <button
                          type="button"
                          className={
                            'btn mr-1 ' +
                            (this.props.active_cat === 'hard'
                              ? 'btn-danger'
                              : 'btn-outline-danger')
                          }
                          onClick={() => {
                            this.props.change_cat('hard');
                          }}
                        >
                          Hard
                        </button>
                      </div>
                      <div className="col-md-6  d-flex justify-content-end">
                        <button
                          type="button"
                          className={
                            !this.state.revisionnotes
                              ? 'btn btn-sm btn-info mr-1'
                              : 'btn btn-sm btn-outline-info mr-1'
                          }
                          onClick={() =>
                            this.setState({
                              revisionnotes: !this.state.revisionnotes,
                            })
                          }
                        >
                          Exam Style Questions
                        </button>
                        <button
                          onClick={() =>
                            this.setState({
                              revisionnotes: !this.state.revisionnotes,
                            })
                          }
                          type="button"
                          className={
                            this.state.revisionnotes
                              ? 'btn btn-sm btn-info mr-1'
                              : 'btn btn-sm btn-outline-info mr-1'
                          }
                        >
                          Revision Notes
                        </button>
                      </div>
                    </div>
                    <div className="row mt-2">
                      {this.state.revisionnotes ? (
                        <div className="col-md-12 mt-2">
                          <div className="card text-left ">
                            <div className="card-body cursor-pointer">
                              <div className="row">
                                <div
                                  className="col-md-12"
                                  style={{
                                    height: 200,
                                    borderRadius: 10,
                                  }}
                                >
                                  <p
                                    to="/"
                                    className="card-link"
                                    style={{
                                      textAlign: 'center',
                                      color: 'blue',
                                    }}
                                  >
                                    PPT, Text, Pictures, Videos Etc
                                  </p>
                                  <textarea
                                    id="userinput8"
                                    rows={8}
                                    className="form-control border-primary"
                                    name="bio"
                                    placeholder=""
                                    defaultValue={''}
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      ) : (
                        <>
                          {!this.props.questionLoading ? (
                            this.props.question_data.length > 0 ? (
                              this.props.question_data.map((item, index) => {
                                return (
                                  <div className="col-xl-12 col-md-6 col-12">
                                    <div
                                      className={
                                        item.reads !== null
                                          ? item.reads.type === 'complete'
                                            ? 'card cursor-pointer bg-success text-white'
                                            : item.reads.type === 'revisit'
                                            ? 'card cursor-pointer bg-danger text-white'
                                            : 'card cursor-pointer'
                                          : 'card cursor-pointer'
                                      }
                                    >
                                      <div className="card-content">
                                        <div
                                          className="card-body pb-1 cursor-pointer"
                                          onClick={() => {
                                            this.props.fullQuestionView(
                                              item,
                                              index,
                                              this.props.question_data[index]
                                            );
                                          }}
                                        >
                                          <div className="row">
                                            <div className="col-12">
                                              <h4 className="mb-1">
                                                <i
                                                  className="cc XRP"
                                                  title="XRP"
                                                />
                                                Question {index + 1}
                                              </h4>
                                              <h6 className="text-black">
                                                {HTMLReactParser(item.question)}
                                              </h6>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                );
                              })
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
                        </>
                      )}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        {/* // Basic form layout section end */}
      </div>
    );
  }
}

class QuestionDetail extends React.Component {
  static contextType = AuthContext;
  constructor(props) {
    super(props);
    this.state = {
      markscheme: false,
      loading: false,
      notesLoading: false,
      notes: '',
    };
  }

  componentDidMount() {
    this.props.question.notes !== null
      ? this.setState({ notes: this.props.question.notes.question_notes })
      : this.setState({ notes: '' });
  }

  handleNextPrevious = (handleNextPrevious) => {
    this.props.handleNextPrevious(handleNextPrevious);
   
  };

  updateQuestionStatus = (question_id, status) => {
    this.setState({ loading: true });
    fetch(api + 'update_question_status', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Application: 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        question_id: question_id,
        question_status: status,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (!json.status) {
          this.setState({ question_data: [] });
        } else {
          this.setState({ question_data: json.data });
        }

        return json;
      })
      .catch((error) => {
        console.error(error);
      })
      .finally(() => {
        this.setState({ loading: false });
      });
  };

  update_notes = (id) => {
    if (this.state.notes === '') {
      toast.error('Please enter notes');
      return false;
    }
    this.setState({ notesLoading: true });
    fetch(api + 'update_question_notes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Application: 'application/json',
        Authorization: this.context.token,
      },
      body: JSON.stringify({
        question_id: id,
        notes: this.state.notes,
      }),
    })
      .then((response) => response.json())
      .then((json) => {
        if (!json.status) {
          this.setState({ question_data: [] });
        } else {
          this.setState({ question_data: json.data });
          toast.success(json.message);
        }
      })
      .catch((error) => {
        console.log(error);
      })
      .finally(() => {
        this.setState({ notesLoading: false });
      });
  };

  render() {
    return (
      // this.setState({ notes: this.props.question.notes.question_notes })
      <div className="content-body">
        {/* Basic form layout section start */}
        <section id="basic-form-layouts">
          <div className="row match-height">
            <div className="col-md-12">
              <div className="card">
                <div className="card-content collapse show">
                  <div className="card-body">
                    <div className="row">
                      <div className="col-md-6">
                        <button
                          type="button"
                          className="btn btn-warning"
                          onClick={() => {
                            this.props.fullQuestionView();
                          }}
                        >
                          View All Questions
                        </button>
                      </div>
                      <div className="col-md-6 d-flex justify-content-end">
                        <button type="button" className="btn btn-success">
                          {this.props.question.subject_code}{' '}
                          {this.props.question.year} ({this.props.question.mark}{' '}
                          Marks)
                        </button>
                      </div>
                    </div>
                    <div className="row mt-2">
                      <div className="col-xl-12 col-md-6 col-12">
                        <div className="card bg-infos">
                          <div className="card-content">
                            <div className="card-body pb-1">
                              <div className="row">
                                <div className="col-12">
                                  <h4 className="text-black mb-1">
                                    <i className="cc XRP" title="XRP" />
                                    Question
                                  </h4>
                                  <h6 className="text-black">
                                    {HTMLReactParser(
                                      this.props.question.question
                                    )}
                                  </h6>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div className="row ">
                      <div className="col-md-12">
                        <div className="form-group row">
                          <div className="col-md-12 mx-auto">
                            <textarea
                              id="userinput8"
                              rows={6}
                              className="form-control border-primary"
                              name="bio"
                              placeholder=""
                              defaultValue={''}
                              onChange={(e) =>
                                this.setState({ notes: e.target.value })
                              }
                              value={this.state.notes}
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div className="row my-4">
                      <div className="col-md-2 col-defined">
                        <button
                          type="button"
                          className="text-capitalize  btn btn-outline-info"
                          onClick={() => this.handleNextPrevious('previous')}
                        >
                          {' '}
                          Previous
                        </button>
                      </div>
                      <div className="col-md-2 col-defined">
                        {' '}
                        {this.state.loading ? (
                          <div
                            className="spinner-border text-primary"
                            role="status"
                          >
                            <span className="sr-only">Loading...</span>
                          </div>
                        ) : (
                          <button
                            type="button"
                            onClick={() => {
                              this.updateQuestionStatus(
                                this.props.question.id,
                                'complete'
                              );
                            }}
                            className="text-capitalize  btn btn-outline-danger"
                          >
                            {' '}
                            Mark as Complete
                          </button>
                        )}
                      </div>
                      <div className="col-md-2 col-defined">
                        {!this.state.show_markup ? (
                          <button
                            className="text-capitalize  btn btn-outline-success"
                            onClick={() => {
                              this.setState({ show_markup: true });
                            }}
                          >
                            Show Mark-scheme
                          </button>
                        ) : (
                          <button
                            className="text-capitalize  btn btn-outline-info"
                            onClick={() => {
                              this.setState({ show_markup: false });
                            }}
                          >
                            Hide Mark-scheme
                          </button>
                        )}
                      </div>
                      <div className="col-md-2 col-defined">
                        {this.state.notesLoading ? (
                          <div
                            className="spinner-border text-primary"
                            role="status"
                          >
                            <span className="sr-only">Loading...</span>
                          </div>
                        ) : (
                          <button
                            type="button"
                            onClick={() => {
                              this.update_notes(
                                this.props.question.id,
                                this.state.notes
                              );
                            }}
                            className="text-capitalize  btn btn-outline-success"
                          >
                            {' '}
                            Update Notes
                          </button>
                        )}
                      </div>
                      <div className="col-md-2 col-defined">
                        {' '}
                        {this.state.loading ? (
                          <div
                            className="spinner-border text-primary"
                            role="status"
                          >
                            <span className="sr-only">Loading...</span>
                          </div>
                        ) : (
                          <button
                            type="button"
                            onClick={() => {
                              this.updateQuestionStatus(
                                this.props.question.id,
                                'revisit'
                              );
                            }}
                            className="text-capitalize  btn btn-outline-danger"
                          >
                            {' '}
                            Mark Revisit
                          </button>
                        )}
                      </div>
                      <div className="col-md-2 col-defined">
                        <button
                          type="button"
                          className="text-capitalize  btn btn-outline-info"
                          onClick={() => this.handleNextPrevious('next')} // Add onClick event for Next button
                        >
                          {' '}
                          Next
                        </button>
                      </div>
                    </div>

                    {this.state.show_markup && (
                      <div className="row mt-4">
                        <div className="col-xl-12 col-md-6 col-12">
                          <div className="card bg-infos">
                            <div className="card-content">
                              <div className="card-body pb-1">
                                <div className="row">
                                  <div className="col-12">
                                    <h6 className="text-black">
                                      {Parser(this.props.question.mark_schema)}
                                    </h6>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          className="col-md-12"
                          style={{
                            justifyContent: 'center',
                            display: 'flex',
                          }}
                        >
                          <button
                            className="btn btn-danger"
                            onClick={() => {
                              this.setState({ show_markup: false });
                            }}
                          >
                            Hide Mark-scheme
                          </button>
                        </div>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    );
  }
}


function Navigate(props) {
  const abcd = useNavigate();
  const location = useLocation();
  return (
    <Question {...props} {...useParams()} navigate={abcd} location={location} />
  );
}

export default (props) => <Navigate {...props} />;
