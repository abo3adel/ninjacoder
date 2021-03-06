import Vue from "vue";
import Component from "vue-class-component";
import setSlotData from "../partials/setSlotData";

@Component({
    template: require("./template.html")
})
export default class CreatePost extends Vue {
    public d = this;
    public file: File;
    public title = "";
    public catModel = []
    public realCat = ''
    public imagePrev: string = "";
    public showPrev: boolean = false;
    public body = "";
    public titleErr: null | boolean = null;
    public imgErr: null | boolean = null;
    public bodyErr: null | boolean = null;
    public loader: null | boolean = null;

    public setCategory() {
        this.d.realCat = (this.d.catModel.sort()).join(',')
    }

    public beforeSubmit(ev) {
        this.d.titleErr = this.d.imgErr = this.d.bodyErr = this.d.loader = null

        if (this.d.title.length < 15) {
            this.d.titleErr = true;
        }

        if (this.d.body.length < 150) {
            this.d.bodyErr = true;
        }

        if (null === this.d.titleErr && null === this.d.bodyErr) {
            this.d.loader = true;
            ev.target.submit();
        }
    }

    public handleFile(ev) {
        this.d.imagePrev = ""
        this.d.showPrev = false
        this.d.imgErr = null

        let file = ev.target.files[0];
        let reader = new FileReader();

        if ((file.size /1024) > 750
        || !/\.(jpe?g|png)$/i.test(file.name)) {
            this.d.imgErr = true
        }
        
        reader.addEventListener(
            "load",
            function() {
                this.d.imagePrev = reader.result;
                this.d.showPrev = true;
            }.bind(this),
            false
        );

        if (file) {
            if (!this.d.imgErr) {
                reader.readAsDataURL(file);
            }
        }
    }

    mounted() {
        // set all to allow parent to use it
        this.d = setSlotData(this, "beforeSubmit", "handleFile", 'setCategory') as this;
    }
}
