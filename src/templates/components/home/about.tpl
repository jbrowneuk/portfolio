{* Smarty template: Portfolio top/home page: about section *}

<section class="page-section background-alternate about-block">
    <header>
        <div class="container mugshot-container">
            <div class="fancy-heading-container blue">
                <h2>Background</h2>
            </div>
            <img class="mugshot" src="/assets/images/about/jason2019.jpg" />
        </div>
    </header>
    <div class="container">
        <div id="skills-grid">
            <!-- Work, column 1 -->
            <article id="work-experience" class="column" name="work">
                <h3>Work Experience</h3>
                <section class="experience">
                    <div class="role">Front end developer &amp; Visual Designer</div>
                    <div class="company">Schlumberger Oilfield UK Plc.</div>
                    <div class="timespan">2018–present</div>
                </section>

                <section class="experience">
                    <div class="role">Web application developer</div>
                    <div class="company">Schlumberger Oilfield UK Plc.</div>
                    <div class="timespan">2016–2018</div>
                </section>

                <section class="experience">
                    <div class="role">Desktop application developer</div>
                    <div class="company">Schlumberger Oilfield UK Plc.</div>
                    <div class="timespan">2013–2016</div>
                </section>

                <section class="experience">
                    <div class="role">Shared C++ library development</div>
                    <div class="company">Schlumberger Oilfield UK Plc.</div>
                    <div class="timespan">2012–2013</div>
                </section>

                <h3>Education</h3>
                <section class="experience">
                    <div class="role">BSc (Hons) Computer Science</div>
                    <div class="company">Nottingham Trent University.</div>
                    <div class="timespan">2009–2012</div>
                </section>
            </article>
            <!-- Skills, column 2 -->
            <article id="skill-set" class="column" name="skills">
                <h3>Skills</h3>
                <h3>Code</h3>
                <section class="experience-charts">
                    <span class="label">HTML</span>
                    {include file="./rating-bar.tpl" amount="5" value="5"}

                    <span class="label">CSS</span>
                    {include file="./rating-bar.tpl" amount="5" value="5"}

                    <span class="label">Angular 2+</span>
                    {include file="./rating-bar.tpl" amount="5" value="4"}

                    <span class="label">JavaScript</span>
                    {include file="./rating-bar.tpl" amount="5" value="4"}

                    <span class="label">PHP</span>
                    {include file="./rating-bar.tpl" amount="5" value="4"}
                </section>

                <h3>Vector Graphics</h3>
                    <h4>Vector Graphics</h4>
                    <span class="label">Inkscape</span>
                    {include file="./rating-bar.tpl" amount="5" value="5"}

                    <span class="label">Affinity Designer</span>
                    {include file="./rating-bar.tpl" amount="5" value="4"}
                </section>

                <h3>Design</h3>
                    <h4>Design</h4>
                    <span class="label">Sketch</span>
                    {include file="./rating-bar.tpl" amount="5" value="4"}

                    <span class="label">icons8 Lunacy</span>
                    {include file="./rating-bar.tpl" amount="5" value="4"}

                    <span class="label">Figma</span>
                    {include file="./rating-bar.tpl" amount="5" value="3"}
                </section>
            </article>
        </div>
    </div>
</section>