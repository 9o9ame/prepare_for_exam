<!doctype html>
<html lang="en">

<x-admin-header-css></x-admin-header-css>
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.min.css')}}" />

<body>
    <!--wrapper-->
    {{-- <x-nav-bar></x-nav-bar> --}}
    <div class="wrapper">
        <div class="app-content center-layout backgroudimage">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-body ">
                    <section class="row navbar-flexbox-container ">
                        <div class="col-12 d-flex align-items-center justify-content-center my-4  ">
                            <div class="col-lg-8 col-md-8 col-10  p-0">
                                <div class="card border-grey border-lighten-3 m-0">
                                    @if (Session::get('success'))
                                    <span class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </span>
                                @endif
                                @if (Session::get('error'))
                                    <span class="alert alert-danger">
                                        {{ Session::get('error') }}
                                    </span>
                                @endif
                                    <div class="card-header  border-0">
                                        <div class="card-title text-center"><img src="{{('assets/logos/main_logo.png')}}"
                                                alt="branding logo" class="sign-logo"></div>
                                    </div>
                                    <div class="card-content">
                                        <p
                                            class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2">
                                            <span>Sign Up to a new account</span>
                                        </p>
                                        <div class="card-body pt-0">
                                            <form class="form-horizontal row" action="{{route('student-register')}}" method="post">
                                                @csrf
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">First Name</label>
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                                                        @error('first_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position"><i
                                                                class="fa-regular fa-user"></i>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Last Name</label>
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" name="last_name">
                                                        @error('last_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position"><i
                                                                class="fa-regular fa-user"></i>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">School Name</label>
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control" placeholder="Enter  Your School Name" value="{{ old('school') }}" name="school">
                                                        @error('school')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position"><i
                                                                class="fa-solid fa-house"></i>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Contact No</label>
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="tel" maxlength="10" value="{{ old('contact') }}" class="form-control" name="contact"  placeholder="Enter Contact No">
                                                        @error('contact')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position"><i
                                                                class="fa-solid fa-phone"></i>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Email Address</label>
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="email" class="form-control" placeholder="Enter Email Address" value="{{ old('email') }}" name="email">
                                                        @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position"><i
                                                                class="fa-regular fa-envelope"></i></div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Date of Birth</label>
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="date" class="form-control" name="date_of_birth" placeholder="Enter date of birth" value="{{ old('date_of_birth') }}">
                                                        <div class="form-control-position"><i
                                                                class="fa-regular fa-calendar-days"></i></div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Country</label>
                                                    <div class="form-group ">
                                                        <select name="country"class="form-control">
                                                            <option value="">Select Country</option>
                                                            <option id="0" value="93">Afghanistan</option>
                                                            <option id="1" value="+358-18">Aland Islands</option>
                                                            <option id="2" value="355">Albania</option>
                                                            <option id="3" value="213">Algeria</option>
                                                            <option id="4" value="+1-684">American Samoa</option>
                                                            <option id="5" value="376">Andorra</option>
                                                            <option id="6" value="244">Angola</option>
                                                            <option id="7" value="+1-264">Anguilla</option>
                                                            <option id="8" value="672">Antarctica</option>
                                                            <option id="9" value="+1-268">Antigua And Barbuda</option>
                                                            <option id="10" value="54">Argentina</option>
                                                            <option id="11" value="374">Armenia</option>
                                                            <option id="12" value="297">Aruba</option>
                                                            <option id="13" value="61">Australia</option>
                                                            <option id="14" value="43">Austria</option>
                                                            <option id="15" value="994">Azerbaijan</option>
                                                            <option id="16" value="+1-242">The Bahamas</option>
                                                            <option id="17" value="973">Bahrain</option>
                                                            <option id="18" value="880">Bangladesh</option>
                                                            <option id="19" value="+1-246">Barbados</option>
                                                            <option id="20" value="375">Belarus</option>
                                                            <option id="21" value="32">Belgium</option>
                                                            <option id="22" value="501">Belize</option>
                                                            <option id="23" value="229">Benin</option>
                                                            <option id="24" value="+1-441">Bermuda</option>
                                                            <option id="25" value="975">Bhutan</option>
                                                            <option id="26" value="591">Bolivia</option>
                                                            <option id="27" value="387">Bosnia and Herzegovina</option>
                                                            <option id="28" value="267">Botswana</option>
                                                            <option id="29" value="0055">Bouvet Island </option>
                                                            <option id="30" value="55">Brazil</option>
                                                            <option id="31" value="246">British Indian Ocean Territory</option>
                                                            <option id="32" value="673">Brunei</option>
                                                            <option id="33" value="359">Bulgaria</option>
                                                            <option id="34" value="226">Burkina Faso</option>
                                                            <option id="35" value="257">Burundi</option>
                                                            <option id="36" value="855">Cambodia</option>
                                                            <option id="37" value="237">Cameroon</option>
                                                            <option id="38" value="1">Canada</option>
                                                            <option id="39" value="238">Cape Verde</option>
                                                            <option id="40" value="+1-345">Cayman Islands</option>
                                                            <option id="41" value="236">Central African Republic</option>
                                                            <option id="42" value="235">Chad</option>
                                                            <option id="43" value="56">Chile</option>
                                                            <option id="44" value="86">China</option>
                                                            <option id="45" value="61">Christmas Island</option>
                                                            <option id="46" value="61">Cocos (Keeling) Islands</option>
                                                            <option id="47" value="57">Colombia</option>
                                                            <option id="48" value="269">Comoros</option>
                                                            <option id="49" value="242">Congo</option>
                                                            <option id="50" value="243">Democratic Republic of the Congo</option>
                                                            <option id="51" value="682">Cook Islands</option>
                                                            <option id="52" value="506">Costa Rica</option>
                                                            <option id="53" value="225">Cote D'Ivoire (Ivory Coast)</option>
                                                            <option id="54" value="385">Croatia</option>
                                                            <option id="55" value="53">Cuba</option>
                                                            <option id="56" value="357">Cyprus</option>
                                                            <option id="57" value="420">Czech Republic</option>
                                                            <option id="58" value="45">Denmark</option>
                                                            <option id="59" value="253">Djibouti</option>
                                                            <option id="60" value="+1-767">Dominica</option>
                                                            <option id="61" value="+1-809 and 1-829">Dominican Republic </option>
                                                            <option id="62" value="670">East Timor</option>
                                                            <option id="63" value="593">Ecuador</option>
                                                            <option id="64" value="20">Egypt</option>
                                                            <option id="65" value="503">El Salvador</option>
                                                            <option id="66" value="240">Equatorial Guinea </option>
                                                            <option id="67" value="291">Eritrea</option>
                                                            <option id="68" value="372">Estonia</option>
                                                            <option id="69" value="251">Ethiopia</option>
                                                            <option id="70" value="500">Falkland Islands </option>
                                                            <option id="71" value="298">Faroe Islands </option>
                                                            <option id="72" value="679">Fiji Islands </option>
                                                            <option id="73" value="358">Finland</option>
                                                            <option id="74" value="33">France</option>
                                                            <option id="75" value="594">French Guiana </option>
                                                            <option id="76" value="689">French Polynesia </option>
                                                            <option id="77" value="262">French Southern Territories</option>
                                                            <option id="78" value="241">Gabon</option>
                                                            <option id="79" value="220">Gambia The</option>
                                                            <option id="80" value="995">Georgia</option>
                                                            <option id="81" value="49">Germany</option>
                                                            <option id="82" value="233">Ghana</option>
                                                            <option id="83" value="350">Gibraltar</option>
                                                            <option id="84" value="30">Greece</option>
                                                            <option id="85" value="299">Greenland</option>
                                                            <option id="86" value="+1-473">Grenada</option>
                                                            <option id="87" value="590">Guadeloupe</option>
                                                            <option id="88" value="+1-671">Guam</option>
                                                            <option id="89" value="502">Guatemala</option>
                                                            <option id="90" value="+44-1481">Guernsey and Alderney</option>
                                                            <option id="91" value="224">Guinea</option>
                                                            <option id="92" value="245">Guinea-Bissau</option>
                                                            <option id="93" value="592">Guyana</option>
                                                            <option id="94" value="509">Haiti</option>
                                                            <option id="95" value="672">Heard Island and McDonald Islands</option>
                                                            <option id="96" value="504">Honduras</option>
                                                            <option id="97" value="852">Hong Kong S.A.R.</option>
                                                            <option id="98" value="36">Hungary</option>
                                                            <option id="99" value="354">Iceland</option>
                                                            <option id="100" value="91">India</option>
                                                            <option id="101" value="62">Indonesia</option>
                                                            <option id="102" value="98">Iran</option>
                                                            <option id="103" value="964">Iraq</option>
                                                            <option id="104" value="353">Ireland</option>
                                                            <option id="105" value="972">Israel</option>
                                                            <option id="106" value="39">Italy</option>
                                                            <option id="107" value="+1-876">Jamaica</option>
                                                            <option id="108" value="81">Japan</option>
                                                            <option id="109" value="+44-1534">Jersey</option>
                                                            <option id="110" value="962">Jordan</option>
                                                            <option id="111" value="7">Kazakhstan</option>
                                                            <option id="112" value="254">Kenya</option>
                                                            <option id="113" value="686">Kiribati</option>
                                                            <option id="114" value="850">North Korea</option>
                                                            <option id="115" value="82">South Korea</option>
                                                            <option id="116" value="965">Kuwait</option>
                                                            <option id="117" value="996">Kyrgyzstan</option>
                                                            <option id="118" value="856">Laos</option>
                                                            <option id="119" value="371">Latvia</option>
                                                            <option id="120" value="961">Lebanon</option>
                                                            <option id="121" value="266">Lesotho</option>
                                                            <option id="122" value="231">Liberia</option>
                                                            <option id="123" value="218">Libya</option>
                                                            <option id="124" value="423">Liechtenstein</option>
                                                            <option id="125" value="370">Lithuania</option>
                                                            <option id="126" value="352">Luxembourg</option>
                                                            <option id="127" value="853">Macau S.A.R.</option>
                                                            <option id="128" value="389">Macedonia</option>
                                                            <option id="129" value="261">Madagascar</option>
                                                            <option id="130" value="265">Malawi</option>
                                                            <option id="131" value="60">Malaysia</option>
                                                            <option id="132" value="960">Maldives</option>
                                                            <option id="133" value="223">Mali</option>
                                                            <option id="134" value="356">Malta</option>
                                                            <option id="135" value="+44-1624">Man (Isle of)</option>
                                                            <option id="136" value="692">Marshall Islands</option>
                                                            <option id="137" value="596">Martinique</option>
                                                            <option id="138" value="222">Mauritania</option>
                                                            <option id="139" value="230">Mauritius</option>
                                                            <option id="140" value="262">Mayotte</option>
                                                            <option id="141" value="52">Mexico</option>
                                                            <option id="142" value="691">Micronesia</option>
                                                            <option id="143" value="373">Moldova</option>
                                                            <option id="144" value="377">Monaco</option>
                                                            <option id="145" value="976">Mongolia</option>
                                                            <option id="146" value="382">Montenegro</option>
                                                            <option id="147" value="+1-664">Montserrat</option>
                                                            <option id="148" value="212">Morocco</option>
                                                            <option id="149" value="258">Mozambique</option>
                                                            <option id="150" value="95">Myanmar</option>
                                                            <option id="151" value="264">Namibia</option>
                                                            <option id="152" value="674">Nauru</option>
                                                            <option id="153" value="977">Nepal</option>
                                                            <option id="154" value="599">Bonaire, Sint Eustatius and Saba</option>
                                                            <option id="155" value="31">Netherlands</option>
                                                            <option id="156" value="687">New Caledonia</option>
                                                            <option id="157" value="64">New Zealand</option>
                                                            <option id="158" value="505">Nicaragua</option>
                                                            <option id="159" value="227">Niger</option>
                                                            <option id="160" value="234">Nigeria</option>
                                                            <option id="161" value="683">Niue</option>
                                                            <option id="162" value="672">Norfolk Island</option>
                                                            <option id="163" value="+1-670">Northern Mariana Islands</option>
                                                            <option id="164" value="47">Norway</option>
                                                            <option id="165" value="968">Oman</option>
                                                            <option id="166" value="92">Pakistan</option>
                                                            <option id="167" value="680">Palau</option>
                                                            <option id="168" value="970">Palestinian Territory Occupied</option>
                                                            <option id="169" value="507">Panama</option>
                                                            <option id="170" value="675">Papua new Guinea</option>
                                                            <option id="171" value="595">Paraguay</option>
                                                            <option id="172" value="51">Peru</option>
                                                            <option id="173" value="63">Philippines</option>
                                                            <option id="174" value="870">Pitcairn Island</option>
                                                            <option id="175" value="48">Poland</option>
                                                            <option id="176" value="351">Portugal</option>
                                                            <option id="177" value="+1-787 and 1-939">Puerto Rico</option>
                                                            <option id="178" value="974">Qatar</option>
                                                            <option id="179" value="262">Reunion</option>
                                                            <option id="180" value="40">Romania</option>
                                                            <option id="181" value="7">Russia</option>
                                                            <option id="182" value="250">Rwanda</option>
                                                            <option id="183" value="290">Saint Helena</option>
                                                            <option id="184" value="+1-869">Saint Kitts And Nevis</option>
                                                            <option id="185" value="+1-758">Saint Lucia</option>
                                                            <option id="186" value="508">Saint Pierre and Miquelon</option>
                                                            <option id="187" value="+1-784">Saint Vincent And The Grenadines</option>
                                                        </select>
                                                        @error('country')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Registraion Type</label>
                                                    <div class="form-group ">
                                                        <select name="registration_type" class="form-control" fdprocessedid="uloxmr">
                                                            <option disabled="">Select Registration Type</option>
                                                            <option value="student">Student</option>
                                                            <option value="teacher">Teacher</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Password</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="form-control " name="password" placeholder="password" >
                                                        <i class="fas fa-eye position-absolute" style="top: 14px; right: 6px;"></i>
                                                        @error('password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-entry-heading">Confirm Password</label>
                                                    <div class="position-relative">
                                                        <input type="password" class="form-control " name="confirm_password" placeholder="password" >
                                                        <i class="fas fa-eye position-absolute" style="top: 14px; right: 6px;"></i>
                                                        @error('confirm_password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="w-100 d-flex">
                                                    <button class="btn btn-info w-50 my-1 btn-rounded  rounded-pill mx-auto" type="submit" >Sign Up</button>
                                                </div>
                                            </form>
                                        </div>
                                        <p class="text-center">Already a user ? Click here to <a class="card-link" href="{{route('/')}}">Sign In</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <x-admin-footer-js></x-admin-footer-js>
</body>

</html>
